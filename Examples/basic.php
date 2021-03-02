<?php 
require_once('../vendor/autoload.php');                                    // load your autoloader.php

use Earl\Core\Uploader;

$uploader = new Uploader('imageUpload', '/');                       // Set the filename and the destination of uploaded files

/**
 * You can use processImage() if your file being uploaded is an 
 * image Or you can use processFile() if your file being 
 * uploaded is an document/text/pdf etc
 */
$uploader->processImage();

/**
 * you can get the uploaded filename with the function getFileName();
 * 
 * you can now store $filename in your database.
 */
$filename = $uploader->getFileName();