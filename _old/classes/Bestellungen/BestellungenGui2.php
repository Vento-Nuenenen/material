<?php
//include_once("BestellungenDatenLog.php");
//include_once("Bestellung.php");

class BestellungenGui{

/*************************************************************************************************************************************
 * Funktionen Für die Materialbestellung                                                                                             *
 *************************************************************************************************************************************/
  public function MaterialBestellen($bestellung){
    /*
     *  Bestellprozess initialisieren
     */
    if ($bestellung == NULL) {
	  $bestellung = new Bestellung();
      $bestellung->setMatbestellung($bestellungenDatenLog->Formulardaten($bestellung->getId(), $bestellung->getVon(), $bestellung->getBis()));
	}

 	if (!isset($_SESSION['bestellung'])){
      $_SESSION['bestellung'] = $bestellung;
	} else {
      $bestellung = $_SESSION['bestellung'];
	} 
      
    if(!isset($_SESSION['step'])) {
        $_SESSION['step'] = 1;
    }
      
    if(!isset($_SESSION['matkat'])) {
        $_SESSION['matkat'] = 0;
    }
    
    switch ($_SESSION['step']){
      case ("1"):   $this->BestellungAnlass($bestellung);
                    break;
      case ("1b"):  $this->KurzfristigeBestellung($bestellung);
                    break;
      case ("2"):   $this->MaterialErfassen($bestellung, $_SESSION['matkat']);
                    break;
    } 
  }
    
    
  private function BestellungAnlass($bestellung) {
    // benötigte Klassen initialisieren  
    $benutzerLog = new BenutzerLog();
      
    /*
     *  Anlassdaten zum Bestellprozess erfassen
     */ ?>
    <h1>Materialbestellung</h1>
    <table id="mat">
      <col width="170">
      <col width="150">
      <col width="150">
      <col width="170">
      <col width="150">
      
      <tr><td colspan="5">&nbsp;</td></tr> <?php

	  if (isset($_SESSION["error"])) { ?>
        <tr><td colspan="5" class="error" align="center"><b><?php echo $_SESSION["error"]; ?></b></td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
	  <?php } ?>
        
        <form action="?seite=MaterialBestellen&action=AnlassUeberpruefen&step=2" method="post">
          <tr>
            <td><b>Bestellung f&uuml;r:</b></td>
            <td>
              <select name="einheit">
                <option value="">&nbsp;</option> <?php 
		        $einheiten = $benutzerLog->EinheitenBenutzer($_SESSION['benutzer']->getId());
                $einheiten->index_setzen(-1);

                while (!$einheiten->listenende()) {
                  $einheit = $einheiten->naechster_eintrag(); 
				  if ($einheit->getBestellen()) { ?>
                    <option value="<?php echo $einheit->getId(); ?>" <?php if($einheit->getId() == $bestellung->getEinheit()) { echo "selected='selected'"; } ?> ><?php echo $einheit->getBezeichnung(); ?></option>
                  <?php } 
				} ?>
                  
              </select>
            </td>
            <td></td>
            <td><b>Kontaktperson:</b></td>
            <td>
              <select name="kontakt" disabled="disabled">
                <?php /* <option value="<?php echo $_SESSION['benutzer']->getId(); ?>"><?php echo $_SESSION['benutzer']->getBenutzername(); ?></option> */ ?>
              </select>
            </td>
          </tr> 

          <tr>
            <td><b>Verf&uuml;gbar am:</b></td>
            <td><input type="text" name="von" class="tcal" value="<?php echo date("d.m.Y", $bestellung->getVon()); ?>" size="10"/></td>
            <td></td>
            <td><b>R&uuml;ckgabe am:</b></td>
            <td><input type="date" name="bis" class="tcal" value="<?php echo date("d.m.Y", $bestellung->getBis()); ?>" size="10"/></td>
          </tr> 

          <tr>
            <td><b>Anlass:</b></td>
            <td colspan="3"><input type="text" name="anlass" value="<?php echo $bestellung->getAnlass(); ?>" size="50"/></td>
            <td alingn="right"></td>
          </tr> 
          
          <tr>
            <td colspan="5">&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="5"><input type="submit" name="weiter" value="Weiter &raquo;" size="10"/></td>
          </tr>

        </form> 
      </tr>
    </table><?php   
  }
    
  private function KurzfristigeBestellung($bestellung) {
    // benötigte Klassen initialisieren  
    $benutzerLog = new BenutzerLog();
      
    /*
     *  Anlassdaten zum Bestellprozess erfassen
     */ ?>
    <h1>Materialbestellung</h1>
    <table id="mat">
      <col width="170">
      <col width="150">
      <col width="150">
      <col width="170">
      <col width="150">
      
      <tr><td colspan="5">&nbsp;</td></tr> <?php

	  /*if (isset($_SESSION["error"])) { ?>
        <tr><td colspan="5" class="error" align="center"><b><?php echo $_SESSION["error"]; ?></b></td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
	  <?php } */?>
        
        <form action="?seite=MaterialBestellen&action=KurzfristigBestaetigen&step=2" method="post">
          <tr>
            <td><b>Bestellung f&uuml;r:</b></td>
            <td><input type="hidden" name="einheit" value="<?php echo $bestellung->getEinheit(); ?>">
              <select  disabled>
                <option value="">&nbsp;</option> <?php 
		        $einheiten = $benutzerLog->EinheitenBenutzer($_SESSION['benutzer']->getId());
                $einheiten->index_setzen(-1);

                while (!$einheiten->listenende()) {
                  $einheit = $einheiten->naechster_eintrag(); 
				  if ($einheit->getBestellen()) { ?>
                    <option value="<?php echo $einheit->getId(); ?>" <?php if($einheit->getId() == $bestellung->getEinheit()) { echo "selected='selected'"; } ?> ><?php echo $einheit->getBezeichnung(); ?></option>
                  <?php } 
				} ?>
                  
              </select>
            </td>
            <td></td>
            <td><b>Kontaktperson:</b></td>
            <td>
              <select name="kontakt" disabled="disabled">
                <?php /* <option value="<?php echo $_SESSION['benutzer']->getId(); ?>"><?php echo $_SESSION['benutzer']->getBenutzername(); ?></option> */ ?>
              </select>
            </td>
          </tr> 

          <tr>
            <td><b>Verf&uuml;gbar am:</b></td>
            <td><input type="text" name="von" class="tcal" value="<?php echo date("d.m.Y", $bestellung->getVon()); ?>" size="10"/></td>
            <td></td>
            <td><b>R&uuml;ckgabe am:</b></td>
            <td><input type="date" name="bis" class="tcal" value="<?php echo date("d.m.Y", $bestellung->getBis()); ?>" size="10"/></td>
          </tr> 

          <tr>
            <td><b>Anlass:</b></td>
            <td colspan="3"><?php echo $bestellung->getAnlass(); ?><input type="hidden" name="anlass" value="<?php echo $bestellung->getAnlass(); ?>"></td>
            <td alingn="right"></td>
          </tr> 
          <tr>
            <td colspan="5">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="5" class="error">
              <table>
                <tr>
                  <td><input type="checkbox" name="kurzfristig" value="1"></td>
                  <td>Diese Bestellung geschieht sehr kurzfristig. Ich nehme zur Kentniss, dass das Materialteam das Material m&ouml;glicherweise nicht mehr rechtzeitig bereitstellen kann.</td>
                </tr> 
              </table>   
            </td>
          </tr>
          <tr>
            <td colspan="5">&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="5"><input type="submit" name="weiter" value="Weiter &raquo;" size="10"/></td>
          </tr>

        </form> 
      </tr>
    </table><?php   
  }
  
  private function MaterialErfassen($bestellung, $kategorie){ 
    $bestellungenDatenLog = new BestellungenDatenLog();

    $formulardaten = $bestellung->setMatbestellung();

      
    $letzteKat = -1;
    $naechsteKat = -1;
    $aktuelleKat = FALSE;
      
    if ($kategorie == 0 && $formulardaten->anzahl_eintraege() > 0) {
              $erste = $formulardaten->eintrag_mit_index(0);
              $kategorie = $erste->getId();
          }

?>
    <h1>Materialbestellung</h1>
    <table id="mat">
      <col width="170">
      <col width="150">
      <col width="150">
      <col width="170">
      <col width="150">
      <tr><td colspan="5">&nbsp;</td></tr> <?php

	  if (isset($_SESSION["error"])) { ?>
        <tr><td colspan="5" class="error" align="center"><b><?php echo $_SESSION["error"]; ?></b></td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
	  <?php } ?>
     

        <form action="?seite=MaterialBestellen&action=BestellungErfassen" method="post">
          <tr>
            <td><b>Bestellung f&uuml;r:</b></td>
            <td><?php echo $bestellungenDatenLog->Einheit($bestellung->getEinheit()); ?></td>
            <td></td>
            <td><b>Kontaktperson:</b></td>
            <td><?php 
			  $kontakte = $bestellungenDatenLog->Kontakte($bestellung->getEinheit());
			  if ($bestellung->getKontakt() == "") {
			    $kontaktperson = $_SESSION['benutzer']->getId();
			  } else {
			    $kontaktperson = $bestellung->getKontakt();
			  } ?>

              <select name="kontakt"> <?php
                $kontakte->index_setzen(-1);
                while (!$kontakte->listenende()) {
                  $benutzer = $kontakte->naechster_eintrag(); ?> 
                  <option value="<?php echo $benutzer->getId(); ?>" <?php if($benutzer->getId() == $kontaktperson) { echo "selected='selected'"; } ?>>
				    <?php echo ucfirst($benutzer->getBenutzername()); ?></option><?php 
			    } ?>
              </select>
            </td>
          </tr>

          <tr>
            <td><b>Verf&uuml;gbar am:</b></td>
            <td><?php echo date("d.m.Y", $bestellung->getVon()); ?></td>
            <td></td>
            <td><b>R&uuml;ckgabe am:</b></td>
            <td><?php echo date("d.m.Y", $bestellung->getBis()); ?></td>
          </tr> 

          <tr>
            <td><b>Anlass:</b></td>
            <td colspan="3"><?php echo $bestellung->getAnlass(); ?></td>
            <td alingn="right"></td>
          </tr> 
          <tr><td colspan="5">&nbsp;</td></tr>
          <tr>
            <td colspan="5"><?php if ($_GET['step'] == 2 ){ ?><input type="submit" name="&Auml;ndern" value="Ablass Daten &Auml;ndern" size="10"/><?php } ?></td>
          </tr>

          <tr><td colspan="5">&nbsp;</td></tr>

          <tr>
            <th colspan="3">Bezeichnung:</th>
            <th>Verf&uuml;gbar:</th>
            <th>Menge:</th>
          </tr><?php
      
          $formulardaten->index_setzen(-1);

          while (!$formulardaten->listenende()) {
            $gruppe = $formulardaten->naechster_eintrag(); ?>

		    <tr id="kategorie">
              <td colspan="5">
			    <?php echo $gruppe->getBezeichnung(); 
                  if ($gruppe->getId() == $kategorie){
                    $aktuelleKat = TRUE;
                    if (!$formulardaten->listenende()){
                      $naechsteKat = $formulardaten->eintrag_mit_index($formulardaten->aktueller_index()+1)->getId();
                    }
                  } else if ($aktuelleKat == FALSE) {
                    $letzteKat = $gruppe->getId();
                  }
                  ?>
               
                <input type="image" src="../../img/br_down.png" width="15" height="15" alt="<?php echo $gruppe->getBezeichnung()." bearbeiten" ?>" 
                  value="<?php echo $gruppe->getId(); ?>" align="right" name="kat<?php echo $gruppe->getId(); ?>" />
               
               <?php /*? >
                <input type="hidden" name="id" value="<?php echo $bestellung->getId(); ?>" />
                <input type="hidden" name="einheit" value="<?php echo $bestellung->getEinheit(); ?>" />
                <input type="hidden" name="von" value="<?php echo $bestellung->getVon(); ?>" />
                <input type="hidden" name="bis" value="<?php echo $bestellung->getBis(); ?>" />
                <input type="hidden" name="anlass" value="<?php echo $bestellung->getAnlass(); ?>" /> */ ?>
              </td>
            </tr> <?php
	
		    $artikelliste = $gruppe->getArtikel();
            $artikelliste->index_setzen(-1);

            if ($gruppe->getId() == $kategorie){
              while (!$artikelliste->listenende()) {
                $artikel = $artikelliste->naechster_eintrag(); ?>
                <tr <?php if ($artikelliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
                  <td colspan="3"><?php echo $artikel->getBezeichnung(); ?><input type="hidden" name="matid[]" value="<?php echo $artikel->getID(); ?>" /></td>
                  <td align="center"><?php echo $artikel->getBestand()-$artikel->getAlltagsbestand()-$artikel->getAusgeliehen(); ?></td>
                  <td align="right">
                    <input type="number" name="menge[]"  size="3" maxlength="3" min="0" max="<?php echo $artikel->getBestand()-$artikel->getAlltagsbestand()-$artikel->getAusgeliehen(); ?>" 
                      value="<?php echo $artikel->getBestellmenge(); ?>" step="<?php echo $artikel->getPackgroesse(); ?>"/>
                  </td>
                </tr> <?php
		      }
              if (!$formulardaten->listenende()){ ?>
                <tr><td colspan="5">&nbsp;</td></tr><?php
              }
            }
	      } ?>
          <tr>
            <td colspan="5">&nbsp;
              <input type="hidden" name="letzteKat" value="<?php echo $letzteKat; ?>" />
              <input type="hidden" name="naechsteKat" value="<?php echo $naechsteKat; ?>" />
            </td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" name="zurueck" value="&laquo; Zur&uuml;ck" size="10"/></td>
            <td align="center"><input type="submit" name="Abbrechen" value="Abbrechen" size="10"/></td>
            <td colspan="2" align="right"> <?php
              if ($naechsteKat < 0){?>
                <input type="submit" name="speichern" value="Speichern" size="10"/> <?php
              } else { ?>
                <input type="submit" name="weiter" value="Weiter &raquo;" size="10"/> <?php
              } ?>
            </td>
          </tr>

		</form>
      </tr>
    </table><?php   
  }
}
?>