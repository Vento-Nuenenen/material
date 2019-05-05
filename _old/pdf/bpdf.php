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
$headertext = "Bediener-Bericht: Datum: ".$speicherarray[5].", Uhrzeit: ".$speicherarray[6];
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
	$this->Ln(5);
	$this->Cell(40);
   
    $this->Cell(120,5,$headertext,1,0,'C');
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

     

$k = 0;	
$i = 16;
$artikelzeile = "";
$gesamtpreis = 0;
$gesamtpreismwst1 = 0;
$gesamtpreismwst2 = 0;

				
				
foreach ($speicherarray as $datensatz)
			{
				$artikelzeile = explode(";", $datensatz);
				
				if ($datensatz == "end")
				{
				$k = 3;
				}
					
					
					
				if ($k == 1 && $artikelzeile[0] != "bedienerdaten")
				{
				
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
				
				if ($datensatz == "end")
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
					
					$pdf->SetXY(10, ($i*3)-4);
					$pdf->Write(6, "_______________________________________________________________________________________________________________________");
					$pdf->SetFont('Arial', 'B', 8);
					$i = $i + 2;
					$pdf->SetXY(85, $i*3);
					$pdf->Cell(12,0,"Gesamtsumme: ".$speicherarray[15],0,0,'R');
					$pdf->SetXY(100, $i*3);
					$pdf->Cell(12,0,$gesamtpreis,0,0,'R');
				}
				
				
				if ($artikelzeile[0] == "bedienerdaten")
				{
				if ($k > 0)
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
					
					$pdf->SetXY(10, ($i*3)-4);
					$pdf->Write(6, "_______________________________________________________________________________________________________________________");
					$pdf->SetFont('Arial', 'B', 8);
					$i = $i + 2;
					$pdf->SetXY(85, $i*3);
					$pdf->Cell(12,0,"Gesamtsumme: ".$speicherarray[15],0,0,'R');
					$pdf->SetXY(100, $i*3);
					$pdf->Cell(12,0,$gesamtpreis,0,0,'R');
					$i = 16;
					$gesamtpreis = 0;
				}	
				$pdf->AddPage();
 				$pdf->SetFont('Arial', '', 8);
	 			$pdf->SetTextColor(0,0,0);
    			$pdf->Write(6, "Bediener Nr: $artikelzeile[1]");
     			$pdf->Ln(3);
				$pdf->Write(6, "Bedienername: $artikelzeile[2]");
     			$pdf->Ln(3);
				$pdf->Ln(1);
				$pdf->Write(6, "_______________________________________________________________________________________________________________________");
				$pdf->Ln(3);
				$pdf->SetFont('Arial', '', 8);
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
				
				
				
				$k = 1;
				}
				
			}	
	
	
	
	
	
	

	
	
$pdf->Output($pdfdatei,"F");

//$pdf->Output();
?>