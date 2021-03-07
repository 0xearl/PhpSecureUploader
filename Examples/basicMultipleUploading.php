<?php 
require_once('../vendor/autoload.php');                                         // load your autoloader.php

use Earl\Core\MultiUploader;

$uploader = new MultiUploader('imageUpload', '/');                              // Set the filename and the destination of uploaded files

/**
 * You can use processImages() if your file being uploaded is an 
 * image Or you can use processDocuments() if your file being 
 * uploaded is a document/text/pdf etc
 */
$uploader->processImages();

/**
 * you can get the uploaded filename with the function getFileName();
 * 
 * you can now store $filename in your database.
 */
$filename = $uploader->getFileNames();