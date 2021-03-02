<?php 

namespace Earl\Core;

use Earl\Core\Exceptions\FileErrorException;
use Earl\Core\Exceptions\InvalidExtensionException;
use Earl\Core\Exceptions\InvalidMimeTypeException;
use Earl\Core\Exceptions\FileTooBigException;

/**
 * @author Earl John Sabalo
 *
 * @class Uploader
 *
 * This class provides a secure uploading library.
 */

class Uploader
{
	private
		/**
		 * @var string $fileName File name of our uploaded file.
		 */
		$fileName,

		/**
		 * @var string $fileNameWithPath File name with the uploaded Path.
		 */
		$fileNameWithPath,

		/**
		 * @var string $path The destination path of uploaded files.
		 */
		$path,

		/**
		 * @var array $file File that contains information about the uploaded file.
		 */
		$file,

		/**
		 * @var string $fileExt File Extension of the uploaded file.
		 */
		$fileExt;

	public
		/**
		 * @var int $validImageSize. Valid Image Size To Be Uploaded. you can set this to any value
		 *		you want. default = 1mb
		 */
		$validImageSize = 10000000,

		/**
		 * @var array $validImageExtensions. Valid Image Extensions To Be Uploaded. you can set this to any
		 *		valid file extensions you want. default = jpeg, jpg, png, svg, gif
		 */
		$validImageExtensions = ['jpeg', 'jpg', 'png', 'svg', 'gif'],

		/**
		 * @var array $validImageMimeTypes. Valid Image Mime Types To Be Uploaded. you can set this to any
		 *		valid mime types. default = image/jpeg, image/png, image/svg+xml, image/gif
		 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types before changing this.
		 */
		$validImageMimeTypes = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/gif'],

		/**
		 * @var int $validFileSize. Valid File Size To Be Uploaded. you can set this to any value you want.
		 *		default value = 5mb
		 */
		$validFileSize = 50000000,

		/**
		 * @var array $validFileExtensions. Valid File Extensions To Be Uploaded. you can set this to any
		 *		valid file extensions you want. default = docx, pdf, txt, xls, doc
		 */
		$validFileExtensions = ['docx', 'pdf', 'txt', 'xls', 'doc'],

		/**
		 * @var array $validFileMimeTypes Valid File Mime Types To Be Uploaded. you can set this to any
		 *		any valid mime types.
		 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types before changing this.
		 */
		$validFileMimeTypes = [
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/msword',
			'application/pdf',
			'text/plain',
			'application/vnd.ms-excel',
			'application/msword'
		];

	/**
	 * @param string $filename name of the file.
	 *
	 * @param string $destination destination path of the uploaded file.
	 *
	 * @return void 
	 */
	function __construct(string $filename, string $destination) {
		$this->path = getcwd().DIRECTORY_SEPARATOR . $destination;
		$this->file = $_FILES[$filename];
		$this->fileExt = end(explode('.', $this->file['name']));
		$this->fileName = uniqid() . sha1_file($this->file['name']) . '.' . $this->fileExt;
		$this->fileNameWithPath = $this->path . $this->fileName;
	}

	/**
	 * This Function Processes The Validation and Uploading of the Image.
	 *
	 * @throws Earl\Core\Exceptions 
	 *
	 * @return bool
	 */
	public function processImage() {

		try {
			$validImage = $this->validateImage();			
		}catch(\Exception $e) {
			return "$e->getMessage()";
		}
		
		if($validImage) {
			if($this->upload()) {
				return true;
			}
		}

		return false;
	}

	/**
	 * This Function Handles the Uploading of Files.
	 *
	 * @return bool
	 */
	protected function upload() {

		$upload = move_uploaded_file($this->file['tmp_name'], $this->fileNameWithPath);

		if($upload) {
			return true;
		}
		return false;
	}

	/**
	 * This function validates our image that is being uploaded.
	 *
	 * @throws Earl\Core\Exceptions;
	 *
	 * @return bool
	 */
	protected function validateImage() {

		if($this->file['error'] > 0) {
			throw new FileErrorException('The file seems to be corrupted, please try again.');
		}

		if(!in_array($this->fileExt, $this->validImageExtensions)) {
			throw new InvalidExtensionException('Invalid File Extension.');
		}elseif(!in_array($this->file['type'], $this->validImageMimeTypes)) {
			throw new InvalidMimeTypeException('Invalid File Mime Type.');
		}

		if($this->file['size'] > $this->validImageSize) {
			throw new FileTooBigException('File Size Too Big.');
		}

		return true;
	}

	/**
	 * This function handles the validation and uploading of our file.
	 *
	 * @throws Earl\Core\Exceptions
	 *
	 * @return bool
	 */
	public function processFile() {

		try {
			$validFile = $this->validateFile();	
		}catch(\Exception $e) {
			return "$e->getMessage()";
		}
		
		if($validFile) {
			if($this->upload) {
				return true;
			}
		}

		return false;
	}

	/**
	 * This function validates our File that is being uploaded.
	 *
	 * @throws Earl\Core\Exceptions;
	 *
	 * @return bool
	 */
	protected function validateFile() {

		if($this->file['error'] > 0) {
			throw new FileErrorException('The file seems to be corrupted, please try again');
		}

		if(!in_array($this->fileExt, $this->validFileExtensions)) {
			throw new InvalidExtensionException('Invalid File Extension.');
		}elseif(!in_array($this->file['type'], $this->validFileMimeTypes)) {
			throw new InvalidMimeTypeException('Invalid File Mime Type.');
		}

		if($this->file['size'] > $this->validFileSize) {
			throw new FileTooBigException('File Size Too Big.');
		}

		return true;
	}

}