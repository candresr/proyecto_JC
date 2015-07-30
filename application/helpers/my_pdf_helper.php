<?php
require 'mpdf/mpdf.php';
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('create_pdf')) {

    function create_pdf($html_data, $file_name = "") {
        if ($file_name == "") {
            $file_name = 'report' . date('dMY');
        }
        
        $mypdf = new mPDF();
        $mypdf->WriteHTML($html_data);
        $mypdf->Output($file_name . 'pdf', 'F');
    }

}

if (!function_exists('createGestion')) {
    
    $mypdf2 = new mPDF();

    function createGestion($html_data, $file_name = "", $header = NULL, $orientacion = NULL) {
        $mypdf2 = new mPDF();
        if (!empty($orientacion)) {
            $mypdf2->AddPages('l');
            $path = PATH_HEADER_FICHA_H;
        } else {
            $path = PATH_HEADER_FICHA_V;
        }
        if (!empty($header)) {
            $html_header = $mypdf2->WriteHTML('<div align="center"><img src="' . $path . '" width = "100%" height = "90px"/></div>');
            $mypdf2->SetHeader($html_header);
            $mypdf2->SetFooter('{DATE d/m/Y}|  |{PAGENO}/{nb}');
        }

        $mypdf2->WriteHTML($html_data);
        $mypdf2->Output($file_name . 'pdf', 'F');
        chmod($file_name . 'pdf', 0777);
    }

}
