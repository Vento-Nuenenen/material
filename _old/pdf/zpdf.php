<?php
/*******************************************************************************
* PDF Generator                                              						*
*                                                                              *
* Version: 2.3                                                                 *
* Datum:   Mai 2014                                                          	*
* Author:  Bertram Schnitzler                                                  *
*                                                                              *
* (c) 2014                                                        				*
*******************************************************************************/


$i=0;
foreach ($speicherarray as $datensatz)
			{
				$speicherarray[$i] = str_replace("undzeichenPattern", "&", $speicherarray[$i]);
				$speicherarray[$i] = str_replace("doppelpunktPattern", ":", $speicherarray[$i]);
				$speicherarray[$i] = str_replace("ß", "ss", $speicherarray[$i]);
				$speicherarray[$i] = str_replace("ö", "oe", $speicherarray[$i]);
				$speicherarray[$i] = str_replace("ä", "ae", $speicherarray[$i]);
				$speicherarray[$i] = str_replace("ü", "ue", $speicherarray[$i]);
				$speicherarray[$i] = str_replace("Ö", "Oe", $speicherarray[$i]);
				$speicherarray[$i] = str_replace("Ä", "Ae", $speicherarray[$i]);
				$speicherarray[$i] = str_replace("Ü", "Ue", $speicherarray[$i]);
				$i++;
			}
			
$speicherarray[6] = str_replace(".", ":", $speicherarray[6]);
$speicherarray[4] = str_replace(".", ":", $speicherarray[4]);
$headertext = "Z-Bericht: NR: ".$speicherarray[2].", Datum: ".$speicherarray[5].", Uhrzeit: ".$speicherarray[6];
$footertext = $speicherarray[9].", ".$speicherarray[8]." ".$speicherarray[7].", ".$speicherarray[10].", ".$speicherarray[11]." ".$speicherarray[12];
require('fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    global $headertext;
	// Logo
    //$this->Image('logo.png',160,8,40);
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    // Move to the right
    $speicherarray[6] = "TEST";
	$this->Ln(5);
	$this->Cell(40);
   
    $this->Cell(100,5,$headertext,1,0,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
   	global $footertext;
    // Position at 1.5 cm from bottom
    $this->SetY(-18);
    // Arial italic 8
    $this->SetFont('Arial','B',8);
    // Page number
    $this->Cell(0,10,$footertext,0,0,'C');
}
}

// PDF erzeugen und Ausgabe
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
      $pdf->SetFont('Arial', '', 8);
	 $pdf->SetTextColor(0,0,0);
    $pdf->Write(6, "Datum letzte Lesung: $speicherarray[3]");
     $pdf->Ln(3);
	 $pdf->Write(6, "Uhrzeit letzte Lesung: $speicherarray[4]");
     $pdf->Ln(3);
	$pdf->Ln(1);
	$pdf->Write(6, "_______________________________________________________________________________________________________________________");
	$pdf->Ln(3);
	$pdf->SetFont('Arial', '', 8);

$k = 0;	
$i = 16;
$artikelzeile = "";
$gesamtpreis = 0;
$gesamtpreismwst1 = 0;
$gesamtpreismwst2 = 0;
				$pdf->SetXY(10, $i*3);
				$pdf->Cell(10,0,"Menge",0,0,'R');
				$pdf->SetXY(25, $i*3);
				$pdf->Cell(50,0,"Bezeichnung",0,0,'L');
     			$pdf->SetXY(80, $i*3);
				$pdf->Cell(12,0,"Einzelpreis",0,0,'R');
				$pdf->SetXY(100, $i*3);
				$pdf->Cell(12,0,"Gesamtpreis",0,0,'R');
				$i++;
			
     			$pdf->SetXY(80, $i*3);
				$pdf->Cell(12,0,"in ".$speicherarray[15],0,0,'R');
				$pdf->SetXY(100, $i*3);
				$pdf->Cell(12,0,"in ".$speicherarray[15],0,0,'R');
				$i++;
				$i++;
				
foreach ($speicherarray as $datensatz)
			{
				
				if ($k == 2 && $datensatz == "end")
				{
				   $k = 3;
				}
				
				if ($k == 2)
				{
				$artikelzeile = explode(";", $datensatz);
			
				$pdf->SetXY(10, $i*3);
				$pdf->Cell(10,0,$artikelzeile[0],0,0,'R');
				$pdf->SetXY(25, $i*3);
				$pdf->Cell(50,0,$artikelzeile[1],0,0,'L');
     			$pdf->SetXY(75, $i*3);
				$pdf->Cell(12,0,$artikelzeile[2],0,0,'R');
				
				$i++;	
				}
				
				if ($k == 1 && $datensatz == "bedienerdaten")
				{
					$gesamtpreis = round($gesamtpreis, 2);
					$pos = strpos($gesamtpreis, ".");
					if ($pos == false)
					{ $gesamtpreis = $gesamtpreis.".00"; }
					else
					{
					if (strlen($gesamtpreis) - $pos == 2)
					{
					$gesamtpreis = $gesamtpreis."0";
					}
					}
					$gesamtpreismwst1 = $gesamtpreismwst1 - $gesamtpreismwst1/(($speicherarray[0]/100)+1);
					$gesamtpreismwst2 = $gesamtpreismwst2 - $gesamtpreismwst2/(($speicherarray[1]/100)+1);
					$gesamtpreismwst1 = round($gesamtpreismwst1, 2);
					$pos = strpos($gesamtpreismwst1, ".");
					if ($pos == false)
					{ $gesamtpreismwst1 = $gesamtpreismwst1.".00"; }
					else
					{	
						if (strlen($gesamtpreismwst1) - $pos == 2)
						{
							$gesamtpreismwst1 = $gesamtpreismwst1."0";
						}
					}
					$gesamtpreismwst2 = round($gesamtpreismwst2, 2);
					$pos = strpos($gesamtpreismwst2, ".");
					if ($pos == false)
					{ $gesamtpreismwst2 = $gesamtpreismwst2.".00"; }
					else
					{	
						if (strlen($gesamtpreismwst2) - $pos == 2)
						{
							$gesamtpreismwst2 = $gesamtpreismwst2."0";
						}
					}
					
					$pdf->SetXY(10, ($i*3)-4);
					$pdf->Write(6, "_______________________________________________________________________________________________________________________");
					$pdf->SetFont('Arial', 'B', 8);
					$i = $i + 2;
					$pdf->SetXY(85, $i*3);
					$pdf->Cell(12,0,"Gesamtsumme: ".$speicherarray[15],0,0,'R');
					$pdf->SetXY(100, $i*3);
					$pdf->Cell(12,0,$gesamtpreis,0,0,'R');
					$i++;
					$i++;
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetXY(85, $i*3);
					$pdf->Cell(12,0,"enthaltene Mwst1 (".$speicherarray[0]."%): ".$speicherarray[15],0,0,'R');
					$pdf->SetXY(100, $i*3);
					$pdf->Cell(12,0,$gesamtpreismwst1,0,0,'R');
					$i++;
					$pdf->SetXY(85, $i*3);
					$pdf->Cell(12,0,"enthaltene Mwst2 (".$speicherarray[1]."%): ".$speicherarray[15],0,0,'R');
					$pdf->SetXY(100, $i*3);
					$pdf->Cell(12,0,$gesamtpreismwst2,0,0,'R');
					$i++;
					
					// STORNOANZAHL einblenden
					if (floatval($speicherarray[17]) > 0)
					{
					$pdf->SetXY(10, $i*3);	
					$pdf->Write(6, "_______________________________________________________________________________________________________________________");
					$i++;
					$i++;
					$i++;
					$pos = strpos($speicherarray[17], ".");
					if ($pos == false)
					{ $speicherarray[17] = $speicherarray[17].".00"; }
					else
					{	
						if (strlen($speicherarray[17]) - $pos == 2)
						{
							$speicherarray[17] = $speicherarray[17]."0";
						}
					}
					
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->SetXY(10, $i*3);
					$pdf->Cell(25,0,"Anzahl Storno: ".$speicherarray[16],0,0,'L');
					$pdf->SetXY(100, $i*3);
					$pdf->Cell(12,0,"Gesamtsumme Storno: ".$speicherarray[15]." ".$speicherarray[17],0,0,'R');
					$i++;
					
					}
					
					
					
					$pdf->AddPage();
					$i = 12;
					
     				$pdf->SetFont('Arial', 'B', 12);
	 				$pdf->SetTextColor(0,0,0);
					$pdf->SetXY(10, $i*3);
    				$pdf->Write(6, "Bediener:");
					$pdf->SetFont('Arial', '', 8);
     				$k = 2;
					$i++;
					$i++;
					$i++;
					$pdf->SetXY(10, $i*3);
					$pdf->Cell(10,0,"Nummer",0,0,'R');
					$pdf->SetXY(25, $i*3);
					$pdf->Cell(50,0,"Name",0,0,'L');
     				$pdf->SetXY(80, $i*3);
					$pdf->Cell(12,0,"Umsatz",0,0,'R');
					$i++;
					$pdf->SetXY(80, $i*3);
					$pdf->Cell(12,0,"in ".$speicherarray[15],0,0,'R');
					$i++;
					
				}
				if ($k == 1)
				{
				$artikelzeile = explode(";", $datensatz);
				$einzelpreis = $artikelzeile[2];
				$pos = strpos($einzelpreis, ".");
				if ($pos == false)
				{ $einzelpreis = $einzelpreis.".00"; }
				else
				{
					if (strlen($einzelpreis) - $pos == 2)
					{
					$einzelpreis = $einzelpreis."0";
					}
				}
				
				$zwischenpreis = floatval($artikelzeile[2])*floatval($artikelzeile[0]);
				if ($artikelzeile[6] == 1) { $gesamtpreismwst1 = $gesamtpreismwst1 + $zwischenpreis; }
				if ($artikelzeile[6] == 2) { $gesamtpreismwst2 = $gesamtpreismwst2 + $zwischenpreis; }
				$gesamtpreis = $gesamtpreis + $zwischenpreis;
				$zwischenpreis = round($zwischenpreis, 2);
				$pos = strpos($zwischenpreis, ".");
				if ($pos == false)
				{ $zwischenpreis = $zwischenpreis.".00"; }
				else
				{
					if (strlen($zwischenpreis) - $pos == 2)
					{
					$zwischenpreis = $zwischenpreis."0";
					}
				}
				
				
				
				$pdf->SetXY(10, $i*3);
				$pdf->Cell(10,0,$artikelzeile[0]."x",0,0,'R');
				$pdf->SetXY(25, $i*3);
				$pdf->Cell(50,0,$artikelzeile[1],0,0,'L');
     			$pdf->SetXY(75, $i*3);
				$pdf->Cell(12,0,$einzelpreis,0,0,'R');
				$pdf->SetXY(100, $i*3);
				$pdf->Cell(12,0,$zwischenpreis,0,0,'R');
				
				$i++;	
				}
				
				
				
				
				if ($datensatz == "artikeldaten")
				{
				$k = 1;
				}
				
			}	
	
	
	
	
	
	

	
	
$pdf->Output($pdfdatei,"F");

//$pdf->Output();
?>