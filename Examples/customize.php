<?php 

require_once('../vendor/autoload.php');                                                     // Load your autoload.php

use Earl\Core\Uploader;                                                                     // Use the uploader

$uploader = new Uploader('testFile', '/');                                                  // Set the filename and the destination of uploaded files


$uploader->setValidDocumentExtensions(['docx', 'doc', 'pdf']);                              // We are setting the valid extensions to only docx, doc and pdf.

$uploader->setValidDocumentMimeTypes(                                                       // setting our document mime-types according to our document extensions.
    [
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/msword',                                                           
    'application/pdf'                                                              
    ]
);

// Setting Valid Extensions and Mime-types to images.

 $uploader->setValidImageExtension(['png', 'jpeg']);

 $uploader->setValidImageMimeTypes(['image/png', 'image/jpeg']);