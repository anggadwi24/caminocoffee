
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename='', $paper, $orientation, $stream=TRUE)
{
    require_once("dompdf/dompdf_config.inc.php");
 
    $dompdf = new DOMPDF();
    $dompdf->set_paper($paper,$orientation);
    $dompdf->load_html($html, 'UTF-8');
    $dompdf->render();
    
    if ($stream) {
        $dompdf->stream($filename.".pdf", array ('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}
function pdf_create1($html, $filename='', $paper, $orientation, $stream=TRUE)
{
    require_once("dompdf/dompdf_config.inc.php");
 
    $dompdf = new DOMPDF();
    $dompdf->set_paper($paper,$orientation);
    $dompdf->load_html($html);
    $dompdf->render();
    
    
    
    if ($stream) {
        $dompdf->stream($filename.".pdf", array ('Attachment' => 0));
      
    } else {
       
        return $dompdf->output();
        
        
    }
}
?>