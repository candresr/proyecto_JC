<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('mergeGestion')) {

    function mergeGestion($arr_file_name, $titulo, $tempDir, $orientation = 'P') {
        require 'PDFMerger/PDFMerger.php';
        $merge = new PDFMerger;
        foreach ($arr_file_name as $row_file) {
            $merge->addPDF($row_file);
        }
        $merge->merge('file', PATH_MACRO_FILES_PDF_FICHA . $titulo . '.pdf', $orientation);
        if (file_exists(PATH_MICRO_FILES_PDF_FICHA . $titulo . '.pdf')) {
            return TRUE;
        } else {
            return FALSE;
        }
        //REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
        //You do not need to give a file path for browser, string, or download - just the name.
    }

}
