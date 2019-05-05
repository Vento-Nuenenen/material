<?php

require(dirname(dirname(dirname(__FILE__)))."/pdf/fpdf.php");

class PDF extends FPDF{
  function Footer(){
    $this->SetY(-15);
    $this->SetFont('Arial', '', 8);
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  } 
}
?>