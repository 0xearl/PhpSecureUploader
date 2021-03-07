<?php

namespace Earl\Core;

use Earl\Core\Exceptions\FileErrorException;
use Earl\Core\Exceptions\FileTooBigException;
use Earl\Core\Exceptions\InvalidExtensionException;
use Earl\Core\Exceptions\InvalidMimeTypeException;

class MultiUploader extends Uploader
{
    private
        /**
         * @var int Total count of how many files are being uploaded
         */
        $count,

        /**
         * @var array $file Contains various information of files being uploaded
         */
        $file,

        /**
         * @var string $destination Destination path of the uploaded files.
         */
        $destination,

        /**
         * @var array $fileNames Holds the unique uploaded filenames.
         */
        $fileNames = [];

    /**
     * @param string $filename name of the file
     * 
     * @param string $destination destination path of the uploaded files
     * 
     * @return void
     */
    function __construct($filename, $destination)
    {
        $this->destination = getcwd() . DIRECTORY_SEPARATOR . $destination;
        $this->file = $_FILES[$filename];
        $this->count = count($this->file['name']);
    }

    /**
     * This function processes the validation and uploading of the images.
     * 
     * @throws \Exception
     * 
     * @return bool;
     */
    public function processImages()
    {

        try {

            $validImage = $this->validateImages();

            if ($validImage) {
                if ($this->multiUpload()) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return false;
    }

    /**
     * This function handles the uploading of the files
     * 
     * @return bool returns ``false`` when the uploading fails.
     */
    private function multiUpload()
    {

        for ($i = 0; $i < $this->count; $i++) {
            $filename = $this->generateUniqueName($this->file['name'][$i]);
            $upload = move_uploaded_file($this->file['tmp_name'][$i], $this->destination . $filename);
            array_push($this->fileNames, $filename);
            if (!$upload) {
                return false;
            }
        }
        return true;
    }

    /**
     * This function generates unique name for the files that are being uploaded
     * to avoid name overwriting, replacing any existing images in the server.
     * 
     * @param string $filename the filename to generate unique name.
     * 
     * @return string
     */
    private function generateUniqueName(string $filename)
    {
        return uniqid() . sha1_file($filename) . '.' . $this->getExtension($filename);
    }

    /**
     * This function validates every each images
     * 
     * @throws FileErrorException|InvalidExtensionException|InvalidMimeTypeException|FileTooBigException.
     * 
     * @return bool
     */
    private function validateImages()
    {

        for ($i = 0; $i < $this->count; $i++) {

            if ($this->file['error'][$i] > 0) {
                throw new FileErrorException('File seems to be corrupted. Please try again.');
                return false;
            }

            if (!in_array($this->getExtension($this->file['name'][$i]), $this->validImageExtensions)) {
                throw new InvalidExtensionException('Invalid image extension.');
                return false;
            } elseif (!in_array($this->file['type'][$i], $this->validImageMimeTypes)) {
                throw new InvalidMimeTypeException('Invalid image mime-type.');
                return false;
            }

            if ($this->file['size'][$i] > $this->validImageSize) {
                throw new FileTooBigException('Image size too big.');
                return false;
            }
        }
        return true;
    }

    /**
     * this function gets the file extension of the file being uploaded.
     * 
     * @param string $filename the filename of the file being uploaded.
     * 
     * @return string the extension of the file being uploaded.
     */
    private function getExtension(string $filename)
    {
        return end(explode('.', $filename));
    }

    /**
     * this function gets all the unique file names that was uploaded in the server.
     * 
     * @param bool $withPath if set to ``true`` it'll return the unique file name with path.
     *             default ``false``.
     * 
     * @return string
     */
    public function getFileNames($withPath = false)
    {
        if ($withPath) {
            return implode(', ', $this->fileNames);
        }
        return implode(', ', $this->fileNames);
    }

    /**
     * This function validates each document files. 
     * 
     * @throws FileErrorException|InvalidExtensionException|InvalidMimeTypeException|FileTooBigException.
     * 
     * @return bool
     */
    public function validateDocuments()
    {
        for ($i = 0; $i < $this->count; $i++) {

            if ($this->file['error'][$i] > 0) {
                throw new FileErrorException('File seems to be corrupted. Please try again.');
                return false;
            }

            if (!in_array($this->getExtension($this->file['name'][$i]), $this->validDocumentExtensions)) {
                throw new InvalidExtensionException('Invalid file extension.');
                return false;
            } elseif (!in_array($this->file['type'][$i], $this->validDocumentMimeTypes)) {
                throw new InvalidMimeTypeException('Invalid file mime-type.');
                return false;
            }

            if ($this->file['size'][$i] > $this->validDocumentSize) {
                throw new FileTooBigException('File size too big.');
                return false;
            }
        }
        return true;
    }

    /**
     * This function processes the validation and uploading of the documents.
     * 
     * @throws \Exception
     * 
     * @return bool;
     */
    public function processDocuments()
    {
        try {
            $validFile = $this->validateDocuments();

            if ($validFile) {
                if ($this->multiUpload()) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return false;
    }
}
