<?php 

require_once('../vendor/autoload.php');                                                    // Load your autoload.php

use Earl\Core\Uploader;                                                             // Use the uploader

$uploader = new Uploader('testFile', '/');                                          // Set the filename and the destination of uploaded files

/**
 * customizing the valid file extensions and mime types
 */
$uploader->validFileExtensions = ['docx', 'doc', 'pdf'];                            // We are setting the valid extensions to only docx, doc and pdf.

/**
 * If you're going to customize the valid extensions please
 * Customize the mimetypes according to the valid mime types
 * Of your custom Exensions
 * 
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types
 */
$uploader->validFileMimeTypes = [
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/msword',                                                           
    'application/pdf'                                                              
];

/**
 * you can also do the same with image.
 */

 $uploader->validImageExtensions = ['png', 'jpeg'];

 $uploader->validImageMimeTypes = ['image/png', 'image/jpeg'];