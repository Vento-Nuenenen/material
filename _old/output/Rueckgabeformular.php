<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(dirname(dirname(__FILE__))."/pdf/fpdf.php");
include_once(dirname(dirname(__FILE__))."/classes/Bestellungen/BestellungenLog.php");

class PDF extends FPDF{
    
  function Footer(){
    $this->SetY(-15);
    $this->SetFont('Arial', '', 8);
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  }
}

class Output{
  function Materialrueckgabe($bestellung){
    $bestLog = new BestellungenLog();
    $bestellung = $bestLog->Bestellung($_GET['bestellung']);

    // PDF Initialisieren
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->Image(dirname(dirname(__FILE__))."/img/logo-nuenenen_grau.png", 10, 10, 50);

    $pdf->SetY(50);    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200);
    $pdf->Cell(0, 7, utf8_decode("Offene Materialrückgabe"), 0, 1, "", 1);

    // Leerzeile
    $pdf->Cell(0, 5, "", 0, 1);

    /*
     * Auftragsinformationen
     */
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, utf8_decode("Einheit:"), 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, $bestellung->getEinheitBez(), 0, 0);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, utf8_decode("Materialausgabe am:"), 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, date("d.m.Y", $bestellung->getVon()), 0, 1);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, utf8_decode("Kontaktperson:"), 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, ucfirst($bestellung->getKontaktBez()), 0, 0);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, utf8_decode("Materialrückgabe am:"), 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, date("d.m.Y", $bestellung->getBis()), 0, 1);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, utf8_decode("Anlass:"), 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, $bestellung->getAnlass(), 0, 1);

    // Leerzeile
    $pdf->Cell(0, 10, "", 0, 1);

    /*
     * Materialliste
     */
    $pdf->SetDrawColor(255);
    $pdf->Cell(110, 5, utf8_decode("Bezeichnung"), 1, 0, "", 1);
    $pdf->Cell(20, 5, utf8_decode("Ausgabe"), 1, 0, "", 1);
    $pdf->Cell(20, 5, utf8_decode("Rückgabe"), 1, 0, "", 1);
    $pdf->Cell(20, 5, utf8_decode("Defekt"), 1, 0, "", 1);
    $pdf->Cell(20, 5, utf8_decode("Offen"), 1, 1, "", 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetFillColor(220);

    $materialliste = $bestellung->getMatbestellung();
    $materialliste->index_setzen(-1);
    while (!$materialliste->listenende()) {
      $material = $materialliste->naechster_eintrag(); 
      if ($materialliste->aktueller_index()%2 != 0) {  
        // gerade Zeilen
        $pdf->Cell(110, 5, utf8_decode($material->getBezeichnung()), "LR", 0, "", 1);
        $pdf->Cell(20, 5, utf8_decode($material->getAusgabemenge()), "LR", 0, "C", 1);
        $pdf->Cell(20, 5, "", "LR", 0, "C", 1);
        $pdf->Cell(20, 5, "", "LR", 0, "C", 1);
        $pdf->Cell(20, 5, "", "LR", 1, "C", 1);   

      } else {
        // ungerade Zeilen
        $pdf->Cell(110, 5, utf8_decode($material->getBezeichnung()), "LR");
        $pdf->Cell(20, 5, utf8_decode($material->getAusgabemenge()), "LR", 0, "C");
        $pdf->Cell(20, 5, "", "LR", 0, "C");
        $pdf->Cell(20, 5, "", "LR", 0, "C");
        $pdf->Cell(20, 5, "", "LR", 1, "C");   
      }
    }

    // Tabellenabschluss
    $pdf->Cell(110, 0, "", "T");
    $pdf->Cell(20, 0, "", "T");
    $pdf->Cell(20, 0, "", "T");
    $pdf->Cell(20, 0, "", "T");
    $pdf->Cell(20, 0, "", "T", 1);

    // Leerzeile
    if($pdf->GetY()>250){
      $pdf->AddPage();
    }

    $pdf->SetY(-50);
    $pdf->SetDrawColor(0);
    
    /*
     * Unterschriften Materialausgabe
     */
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 7, utf8_decode("Materialrücknahme"), 0, 1);
    $y = $pdf->GetY();
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(40, 5, utf8_decode("Rücknahme durch:"), 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 0);
    $pdf->Cell(40, 5, utf8_decode("Rückgabe durch:"), 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 1);
    $pdf->Cell(40, 5, utf8_decode("Rücknahme am:"), 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 0);
    $pdf->Cell(40, 5, utf8_decode("Rückgabe am:"), 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 1);
    $pdf->Cell(40, 10, utf8_decode("Unterschrift:"), 0, 0);
    $pdf->Cell(55, 10, "........................................", 0, 0);
    $pdf->Cell(40, 10, utf8_decode("Unterschrift:"), 0, 0);
    $pdf->Cell(55, 10, "........................................", 0, 1);
    $pdf->SetY($y-1);
    $pdf->Cell(95, 20, "", 1, 0);
    $pdf->Cell(0, 20, "", 1, 1);

    $pdf->Output();
  }
}

$out = new Output();
$out->Materialrueckgabe(NULL);

?>