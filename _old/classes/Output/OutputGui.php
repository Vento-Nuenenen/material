<?php
include_once("OutputLog.php");
include_once("Mitteilung.php");
include_once("pdf.php");

class OutputGui{

/*************************************************************************************************************************************
 * Funktionen Für die Materialbestellung                                                                                             *
 *************************************************************************************************************************************/

  function MitteilungsListe($mitteilungen, $bearbeiten) {
    ?>
          <?php if ($bearbeiten) { ?>
<h1>Mitteilungen</h1>
    <?php } ?>
    <table id="mat">
      <col width="300">
      <col width="570">
      <col width="30">
      <?php if ($bearbeiten) { ?>
      <tr>
        <td colspan="2"></td>
        <td><a href="?seite=MitteilungErfassen"><img src="img/neu.png" width="10" height="10" border="0"/></td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="2"><hr noshade size="1" align="left"></td>
        <td></td>
      </tr>
	  <?php
      
	  $mitteilungen->index_setzen(-1);
      while (!$mitteilungen->listenende()) { 
        $meldung = $mitteilungen->naechster_eintrag(); ?>
        <tr>
          <td><b><?php echo ucfirst($meldung->getBenutzerBez()); ?></b></td>
          <td align="right"><?php echo date("d.m.Y H:i", $meldung->getDatum());?></td>
          <td rowspan="2">
            <?php if ($bearbeiten) { ?>
              <a href="?seite=MitteilungBearbeiten&index=<?php echo $mitteilungen->aktueller_index(); ?>"><img src="img/bearbeiten.png" width="10" height="10" border="0"/></a>
              <a href="?seite=MitteilungLoeschen&index=<?php echo $mitteilungen->aktueller_index(); ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td colspan="2"><?php echo str_replace("\n", "<br>",$meldung->getMitteilung()); ?></td>
        </tr>
        <td colspan="2"><hr noshade size="1" align="left"></td>
        <td></td>
      <?php } ?>
    </table>
    <?
  }

  function MitteilungBearbeiten($meldung) {    
 
    if ($meldung == NULL) {
      $meldung = new Mitteilung();  
      $meldung->setBenutzer($_SESSION['benutzer']->getId());
      $meldung->setBenutzerBez($_SESSION['benutzer']->getBenutzername());
	  echo "<h1>Mitteilung erfassen</h1> ";	
    } else {
	  echo "<h1>Mitteilung bearbeiten</h1> ";	
    }
    ?>
    <form action="?seite=MitteilungenVerwalten&action=MitteilungSpeichern" method="post">
      <p>&nbsp;</p>
      <table>
        <col width="300">
        <col width="600">
        <tr>
          <td><b><?php echo ucfirst($meldung->getBenutzerBez()); ?><input type="hidden" name="benutzer" value="<?php echo $meldung->getBenutzer(); ?>"/></b></td>
          <td align="right"><?php if ($meldung->getDatum() != NULL) { echo date("d.m.Y H:i", $meldung->getDatum()); }  else { echo date("d.m.Y H:i:s"); }?></td>
        </tr>
        <tr>
          <td colspan="2"><textarea rows="10" cols="100" name="mitteilung"><?php echo $meldung->getMitteilung(); ?></textarea></td>
        </tr>
        <tr>
          <td colspan="8"><input type="submit" name="Speichern" value="Speichern" /><input type="submit" name="Abbrechen" value="Abbrechen" /><input type="hidden" name="id" value="<?php echo $meldung->getId(); ?>" /></td>
        </tr>
      </table>
    </form>
    <?
  }
  
  function MitteilungLoeschen($meldung) {
    ?>
    <h1>Mitteilung l&ouml;schen</h1>
    <form action="?seite=MitteilungenVerwalten&action=MitteilungLoeschen" method="post">
      <table id="mat">
        <col width="300">
        <col width="570">
        <tr>
          <td colspan="2">Soll die folgende Mitteilung wirklich gel&ouml;scht werden?</td>
        </tr>
        <tr>
          <td colspan="2"><hr noshade size="1" align="left"></td>
        </tr>
        <tr>
          <td><b><?php echo ucfirst($meldung->getBenutzerBez()); ?></b></td>
          <td align="right"><?php echo date("d.m.Y H:i:s", $meldung->getDatum());?></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo $meldung->getMitteilung(); ?></td>
        </tr>
        <td colspan="2"><hr noshade size="1" align="left"></td>
        <tr>
          <td colspan="2"><input type="submit" name="L&ouml;schen" value="L&ouml;schen" /><input type="submit" name="Abbrechen" value="Abbrechen" /><input type="hidden" name="id" value="<?php echo $meldung->getId(); ?>" /></td>
        </tr>
    </table>
    </form>
    <?
      
  }
  
  function Materialausgabe($bestellung){
    // PDF Initialisieren
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->Image("img/logo-nuenenen_grau.png", 10, 10, 50);
    
    $pdf->SetY(50);    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200);
    $pdf->Cell(0, 7, "Materialausgabe / -rücknahme", 0, 1, "", 1);

    // Leerzeile
    $pdf->Cell(0, 5, "", 0, 1);

    /*
     * Auftragsinformationen
     */
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Einheit:", 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, "Aetna", 0, 0);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Materialausgabe am:", 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, "30.04.2015", 0, 1);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Kontaktperson:", 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, "Latos", 0, 0);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Materialrückgabe am:", 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, "02.05.2015", 0, 1);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Anlass:", 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, "Anlass", 0, 1);

    // Leerzeile
    $pdf->Cell(0, 10, "", 0, 1);

    /*
     * Materialliste
     */
    $pdf->SetDrawColor(255);
    $pdf->Cell(115, 5, "Bezeichnung", 1, 0, "", 1);
    $pdf->Cell(25, 5, "Bestellt", 1, 0, "", 1);
    $pdf->Cell(25, 5, "Ausgabe", 1, 0, "", 1);
    $pdf->Cell(25, 5, "Rückgabe", 1, 1, "", 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetFillColor(220);

    // ungerade Zeilen
    $pdf->Cell(115, 5, "Bezeichnung", "LR");
    $pdf->Cell(25, 5, "Bestellt", "LR");
    $pdf->Cell(25, 5, "Ausgabe", "LR");
    $pdf->Cell(25, 5, "Rückgabe", "LR", 1);

    // gerade Zeilen
    $pdf->Cell(115, 5, "Bezeichnung", "LR", 0, "", 1);
    $pdf->Cell(25, 5, "Bestellt", "LR", 0, "", 1);
    $pdf->Cell(25, 5, "Ausgabe", "LR", 0, "", 1);
    $pdf->Cell(25, 5, "Rückgabe", "LR", 1, "", 1);

    // Tabellenabschluss
    $pdf->Cell(115, 0, "", "T");
    $pdf->Cell(25, 0, "", "T");
    $pdf->Cell(25, 0, "", "T");
    $pdf->Cell(25, 0, "", "T", 1);

    // Leerzeile
    if($pdf->GetY()>250){
      $pdf->AddPage();
    }

    $pdf->SetY(-80);
    $pdf->SetDrawColor(0);
    /*
     * Unterschriften Materialausgabe
     */
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 7, "Materialausgabe", 0, 1);
    $y = $pdf->GetY();
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(40, 5, "Ausgabe durch:", 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 0);
    $pdf->Cell(40, 5, "Entgegennahme durch:", 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 1);
    $pdf->Cell(40, 5, "Ausgabe am:", 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 0);
    $pdf->Cell(40, 5, "Entgegennahme am:", 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 1);
    $pdf->Cell(40, 10, "Unterschrift:", 0, 0);
    $pdf->Cell(55, 10, "........................................", 0, 0);
    $pdf->Cell(40, 10, "Unterschrift:", 0, 0);
    $pdf->Cell(55, 10, "........................................", 0, 1);
    $pdf->SetY($y-1);
    $pdf->Cell(95, 20, "", 1, 0);
    $pdf->Cell(0, 20, "", 1, 1);

    // Leerzeile
    $pdf->Cell(0, 5, "", 0, 1);

    /*
     * Unterschriften Materialausgabe
     */
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 7, "Materialrücknahme", 0, 1);
    $y = $pdf->GetY();
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(40, 5, "Rücknahme durch:", 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 0);
    $pdf->Cell(40, 5, "Rückgabe durch:", 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 1);
    $pdf->Cell(40, 5, "Rücknahme am:", 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 0);
    $pdf->Cell(40, 5, "Rückgabe am:", 0, 0);
    $pdf->Cell(55, 5, "........................................", 0, 1);
    $pdf->Cell(40, 10, "Unterschrift:", 0, 0);
    $pdf->Cell(55, 10, "........................................", 0, 0);
    $pdf->Cell(40, 10, "Unterschrift:", 0, 0);
    $pdf->Cell(55, 10, "........................................", 0, 1);
    $pdf->SetY($y-1);
    $pdf->Cell(95, 20, "", 1, 0);
    $pdf->Cell(0, 20, "", 1, 1);

    $pdf->Output();
  }
  
  
  function AusstehendesMaterial($bestellung){
    // PDF Initialisieren
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->Image("img/logo-nuenenen_grau.png", 10, 10, 50);
    
    $pdf->SetY(50);    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(200);
    $pdf->Cell(0, 7, "Ausstehendes Material", 0, 1, "", 1);

    // Leerzeile
    $pdf->Cell(0, 5, "", 0, 1);

    /*
     * Auftragsinformationen
     */
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Einheit:", 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, "Aetna", 0, 0);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Materialausgabe am:", 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, "30.04.2015", 0, 1);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Kontaktperson:", 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, "Latos", 0, 0);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Materialrückgabe am:", 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 5, "02.05.2015", 0, 1);
    $pdf->SetFont('Arial', 'BI', 10);
    $pdf->Cell(40, 5, "Anlass:", 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, "Anlass", 0, 1);

    // Leerzeile
    $pdf->Cell(0, 10, "", 0, 1);

    /*
     * Materialliste
     */
    $pdf->SetDrawColor(255);
    $pdf->Cell(110, 5, "Bezeichnung", 1, 0, "", 1);
    $pdf->Cell(20, 5, "Ausgabe", 1, 0, "", 1);
    $pdf->Cell(20, 5, "R&uuml;ckgabe", 1, 0, "", 1);
    $pdf->Cell(20, 5, "Defekt", 1, 0, "", 1);
    $pdf->Cell(20, 5, "Offen", 1, 1, "", 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetFillColor(220);

    // ungerade Zeilen
    $pdf->Cell(110, 5, "Bezeichnung", "LR");
    $pdf->Cell(20, 5, "Ausgabe", "LR");
    $pdf->Cell(20, 5, "R&uuml;ckgabe", "LR");
    $pdf->Cell(20, 5, "Defekt", "LR");
    $pdf->Cell(20, 5, "Offen", "LR", 1);

    // gerade Zeilen
    $pdf->Cell(110, 5, "Bezeichnung", "LR", 0, "", 1);
    $pdf->Cell(20, 5, "Ausgabe", "LR", 0, "", 1);
    $pdf->Cell(20, 5, "R&uuml;ckgabe", "LR", 0, "", 1);
    $pdf->Cell(20, 5, "Defekt", "LR", 0, "", 1);
    $pdf->Cell(20, 5, "Offen", "LR", 1, "", 1);

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

    $pdf->SetY(-80);
    $pdf->SetDrawColor(0);
    

    $pdf->Output();
  }
}
?>