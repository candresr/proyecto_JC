<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename)
{
    define("FPDF_FONTPATH", "mpdf/font/'");
    require_once("mpdf/mpdf.php");
    $mpdf = new mPDF();
    $mpdf->WriteHTML($html);
    $mpdf->Output($filename,'D');
	$mpdf->exit();
}
?>
