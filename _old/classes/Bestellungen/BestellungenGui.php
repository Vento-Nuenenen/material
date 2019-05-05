<?php
include_once("BestellungenLog.php");
include_once("Bestellung.php");


class BestellungenGui{

/*************************************************************************************************************************************
 * Funktionen Für die Materialbestellung                                                                                             *
 *************************************************************************************************************************************/

  function Materialbestellung($bestellung) {
	$bestellungenLog = new BestellungenLog();
	$benutzerLog = new BenutzerLog();

	if ($bestellung == NULL) {
	  $bestellung = new Bestellung();	
	}

 	if (!isset($_SESSION['bestellung'])){
      $_SESSION['bestellung'] = $bestellung;
	} else {
      $bestellung = $_SESSION['bestellung'];
	}

	if ($_GET['step'] > 1) {
	  $formulardaten = $bestellungenLog->Formulardaten($bestellung->getId(), $bestellung->getVon(), $bestellung->getBis());
	}?>

    <h1>Materialbestellung</h1>
    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">
      <tr><td colspan="5">&nbsp;</td></tr> <?php

	  if (isset($_SESSION["error"])) { ?>
        <tr><td colspan="5" class="error" align="center"><b><?php echo $_SESSION["error"]; ?></b></td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
	  <?php } ?>

      <?php if (!isset($_GET['step']) || $_GET['step'] == 1){ ?>
        <form action="?seite=MaterialBestellen&action=DatumSpeichern&step=2" method="post">
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
            <td alingn="right"><input type="submit" name="weiter" value="Weiter &raquo;" size="10"/></td>
          </tr> 

        </form>

      <?php } else if ($_GET['step'] == 2 ){ ?>
        <form action="?seite=MaterialBestellen&action=DatumAendern&step=1" method="post">
          <tr>
            <td colspan="5"><?php if ($_GET['step'] == 2 ){ ?><input type="submit" name="&Auml;ndern" value="&Auml;ndern" size="10"/><?php } ?></td>
          </tr>
        </form>
        <form action="?seite=MaterialBestellen&action=BestellungSpeichern&step=3" method="post">
          <tr>
            <td><b>Bestellung f&uuml;r:</b></td>
            <td><?php echo $bestellungenLog->Einheit($bestellung->getEinheit()); ?></td>
            <td></td>
            <td><b>Kontaktperson:</b></td>
            <td><?php 
			  $kontakte = $bestellungenLog->Kontakte($bestellung->getEinheit());

			  if ($bestellung->getKontakt() == "") {
			    $kontaktperson = $_SESSION['benutzer']->getId();
			  } else {
			    $kontaktperson = $bestellung->getKontakt();
			  } ?>

              <select name="kontakt"> <?php
                $kontakte->index_setzen(-1);
				$i = FALSE;
                while (!$kontakte->listenende()) {
                  $benutzer = $kontakte->naechster_eintrag(); ?> 
                  <option value="<?php echo $benutzer->getId(); ?>" <?php if($benutzer->getId() == $kontaktperson) { echo "select='select'";  $i = TRUE;} ?>>
				    <?php echo ucfirst($benutzer->getBenutzername()); ?></option><?php 
				  if (!$i) { ?>
                    <option value="<?php echo $_SESSION['benutzer']->getId(); ?>" select='select' >
				    <?php echo ucfirst($_SESSION['benutzer']->getBenutzername()); ?></option><?php 
				  }
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
            <th colspan="3">Bezeichnung:</th>
            <th>Verf&uuml;gbar:</th>
            <th>Menge:</th>
          </tr><?php
                                            
          $formulardaten->index_setzen(-1);

          while (!$formulardaten->listenende()) {
            $gruppe = $formulardaten->naechster_eintrag(); ?>

		    <tr>
              <th colspan="5">
			    <?php echo $gruppe->getBezeichnung(); ?>
                <input type="hidden" name="id" value="<?php echo $bestellung->getId(); ?>" />
                <input type="hidden" name="einheit" value="<?php echo $bestellung->getEinheit(); ?>" />
                <input type="hidden" name="von" value="<?php echo $bestellung->getVon(); ?>" />
                <input type="hidden" name="bis" value="<?php echo $bestellung->getBis(); ?>" />
                <input type="hidden" name="anlass" value="<?php echo $bestellung->getAnlass(); ?>" />
              </th>
            </tr> <?php
	
		    $artikelliste = $gruppe->getArtikel();
            $artikelliste->index_setzen(-1);

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
		    }?>
            
            <tr><td colspan="5">&nbsp;</td></tr><?php
	      } ?>
          
          <tr><td colspan="5"><input type="submit" name="Speichern" value="Speichern" size="10"/></td></tr>

		</form><?php

	  } else if ($_GET['step'] >= 3 ){ ?>
        <tr>
          <td><b>Bestellung f&uuml;r:</b></td>
          <td><?php echo $bestellungenLog->Einheit($bestellung->getEinheit()); ?></td>
          <td></td>
          <td><b>Kontaktperson:</b></td>
          <td> <?php 
            $kontakte = $bestellungenLog->AlleKontakte();
            $kontakte->index_setzen(-1);
            while (!$kontakte->listenende()) {
              $benutzer = $kontakte->naechster_eintrag(); 
              if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
            }?>
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
          <th colspan="4">Bezeichnung:</th>
          <th>Menge:</th>
        </tr> <?php

        $formulardaten->index_setzen(-1);
        while (!$formulardaten->listenende()) {
          $gruppe = $formulardaten->naechster_eintrag(); ?>
		  <tr>
            <th colspan="5"><?php echo $gruppe->getBezeichnung(); ?></th>
          </tr> <?php		

		  $artikelliste = $gruppe->getArtikel();
          $artikelliste->index_setzen(-1);

          while (!$artikelliste->listenende()) {
            $artikel = $artikelliste->naechster_eintrag(); ?>
            <tr <?php if ($artikelliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
              <td colspan="4"><?php echo $artikel->getBezeichnung(); ?></td>
              <td align="right"><?php echo $artikel->getBestellmenge(); ?></td>
            </tr> <?php
		  } ?>
        
          <tr><td colspan="5">&nbsp;</td></tr><?php
	    }

		if ($_GET['step'] == 3 ){ ?>
          <tr>
            <td colspan="2">
              <form action="?seite=MaterialBestellen&action=BestellungBearbeiten&step=2" method="post"><input type="submit" name="Bearbeiten" value="Bearbeiten" size="10"/></form>
            </td>
            <td colspan="3" align="right">
              <form action="?seite=MaterialBestellen&action=BestellungAbsenden&step=4" method="post"><input type="submit" name="Weiter" value="Weiter" size="10"/></form>
            </td>
          </tr> <?php 
                                 
        } else if ($_GET['step'] == 4 ){ ?>
		  <tr>
            <td colspan="5" class="error">Mit dem Best&auml;tigen der Bestellung wird diese an das Material-Team versendet, und kann nicht mehr bearbeitet werden!</td>
          </tr>
          <tr>
            <td colspan="2">
              <form action="?seite=MaterialBestellen&step=3" method="post"><input type="submit" name="Abbrechen" value="Abbrechen" size="10"/></form>
            </td>
            <td colspan="3" align="right">
              <form action="?seite=MeineBestellungen&action=BestellungBestaetigen&id=<?php echo $bestellung->getId(); ?>" method="post"><input type="submit" name="Best&auml;tigen" value="Best&auml;tigen" size="10"/></form>
            </td>
          </tr> <?php 
        }
	  } ?>
    </table> <?php
  }


  function BestellungenListe($bestellungen){ ?>

    <h1>Meine Materialbestellungen</h1> 

    <table id="mat">
      <col width="100">
      <col width="150">
      <col width="100">
      <col width="100">
      <col width="60">
      <col width="60">

      <tr>
        <th><b>Einheit:</b></th>
        <th><b>Anlass:</b></th>
        <th><b>Vom:</b></th>
        <th><b>Bis:</b></th>
        <th><b>Status:</b></th>
        <th></th>
      </tr><?php

	  $bestellungen->index_setzen(-1);
	  $i=0;

      while (!$bestellungen->listenende()) { 
	    $bestellung = $bestellungen->naechster_eintrag(); ?>
        <tr <?php if ($i++%2 != 0) { echo "id='uneven'"; } ?>>
          <td><?php echo $bestellung->getEinheitBez(); ?></td>
          <td><?php echo $bestellung->getAnlass(); ?></td>
          <td><?php echo date("d.m.Y", $bestellung->getVon()); ?></td>
          <td><?php echo date("d.m.Y", $bestellung->getBis()); ?></td>
          <td><?php 
            switch($bestellung->getStatus()){
              case "0": ?>
                <img src="img/rot.png" width="10" height="10" alt="gespeichert"/><?php
                break;
              case "1": ?>
                <img src="img/grau.png" width="10" height="10" alt="eingereicht"/><?php
                break;
              case "2": ?>
                <img src="img/gelb.png" width="10" height="10" alt="angenommen"/><?php
                break;
              case "3": ?>
                <img src="img/gruen.png" width="10" height="10" alt="bereitgestellt"/><?php
                break;
              case "4": ?>
                <img src="img/dunkelgruen.png" width="10" height="10" alt="abgeschlossen"/><?php
                break;
            }?>
          </td>
          <td>
            <a href="?seite=BestellungAnzeigen&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/anzeigen-suchen.png" width="10" height="10" border="0"/></a> <?php
            if ($bestellung->getStatus() < 1) { ?>
              <a href="?seite=MaterialBestellen&index=<?php echo $bestellungen->aktueller_index(); ?>&step=2"><img src="img/bearbeiten.png" width="10" height="10" border="0"/></a>
              <a href="?seite=BestellungLoeschen&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a>  <?php
            } ?>
          </td>
        </tr> <?php 
      } ?>

    </table>

    <p>&nbsp;</p>

    <table>
      <col width="12">
      <col width="90">
      <col width="12">
      <col width="90">
      <col width="12">
      <col width="90">
      <col width="12">
      <col width="90">
      <col width="12">
      <col width="90">
    
      <tr><td colspan="10"><b>Status:</b></td></tr>
      <tr>
        <td><img src="img/rot.png" width="10" height="10" alt="gespeichert"/></td>
        <td>gespeichert</td>
        <td><img src="img/grau.png" width="10" height="10" alt="eingereicht"/></td>
        <td>eingereicht</td>
        <td><img src="img/gelb.png" width="10" height="10" alt="angenommen"/></td>
        <td>angenommen</td>
        <td><img src="img/gruen.png" width="10" height="10" alt="bereitgestellt"/></td>
        <td>bereitgestellt</td>
        <td><img src="img/dunkelgruen.png" width="10" height="10" alt="abgeschlossen"/></td>
        <td>abgeschlossen</td>
      </tr>

    </table> <?php 
  } 


  function BestellungAnzeigen($bestellung){

	$bestellungenLog = new BestellungenLog(); ?>

    <h1>Materialbestellung</h1>

    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">

      <tr><td colspan="5">&nbsp;</td></tr>
      <tr>
        <td><b>Bestellung f&uuml;r:</b></td>
        <td><?php echo $bestellung->getEinheitBez(); ?></td>
        <td></td>
        <td><b>Kontaktperson:</b></td>
        <td> <?php 
          $kontakte = $bestellungenLog->AlleKontakte();
          $kontakte->index_setzen(-1);
          while (!$kontakte->listenende()) {
            $benutzer = $kontakte->naechster_eintrag(); 
            if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
          }?>
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
        <th colspan="4">Bezeichnung:</th>
        <th>Menge:</th>
      </tr> <?php
      
  	  $materialliste = $bestellung->getMatbestellung();
      $materialliste->index_setzen(-1);
      
      while (!$materialliste->listenende()) {
        $material = $materialliste->naechster_eintrag(); ?>
        <tr <?php if ($materialliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
          <td colspan="4"><?php echo $material->getBezeichnung(); ?></td>
          <td align="right"><?php echo $material->getBestellmenge(); ?></td>
        </tr> <?php
      } ?>
      <tr><td colspan="5">&nbsp;</td></tr>

    </table>

	<p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">&laquo; Zur&uuml;ck</a></p> <?php
  }




/*************************************************************************************************************************************
 * Funktionen für die Verwaltung der Materialbestellungen                                                                            *
 *************************************************************************************************************************************/

  function BestellungenUebersicht() {
	$bestellungenLog = new BestellungenLog();
    $status = $bestellungenLog->StatusUebersicht(); ?>

    <h1>&Uuml;bersicht Materialbestellung</h1>

    <table id="mat">
      <col width="60">
      <col width="130">
      <col width="130">
      <col width="130">
      <col width="130">
      <col width="130">

      <tr>
        <td></td>
        <th>&nbsp;<img src="img/rot.png" width="10" height="10" alt="gespeichert"/> &nbsp; gespeichert</th>
        <th>&nbsp;<img src="img/grau.png" width="10" height="10" alt="eingereicht"/> &nbsp; eingereicht</th>
        <th>&nbsp;<img src="img/gelb.png" width="10" height="10" alt="angenommen"/> &nbsp; angenommen</th>
        <th>&nbsp;<img src="img/gruen.png" width="10" height="10" alt="bereitgestellt"/> &nbsp; bereitgestellt</th>
        <th>&nbsp;<img src="img/dunkelgruen.png" width="10" height="10" alt="abgeschlossen"/> &nbsp; abgeschlossen</th>
      </tr>

      <tr>
        <th>Anzahl</th>
        <td align="center"><?php echo $status[0]; ?></td>
        <td align="center"><?php echo $status[1]; ?></td>
        <td align="center"><?php echo $status[2]; ?></td>
        <td align="center"><?php echo $status[3]; ?></td>
        <td align="center"><?php echo $status[4]; ?></td>
      </tr>

    </table> <?php      
  }


  function MaterialbestellungBearbeiten($bestellung) {

	$bestellungenLog = new BestellungenLog();
	$benutzerLog = new BenutzerLog();

	if ($bestellung == NULL) {
	  $bestellung = new Bestellung();	
	}

  	if (!isset($_SESSION['bestellung'])){
      $_SESSION['bestellung'] = $bestellung;
	} else {
      $bestellung = $_SESSION['bestellung'];
	}

	if ($_GET['step'] > 1) {
	  $formulardaten = $bestellungenLog->Formulardaten($bestellung->getId(), $bestellung->getVon(), $bestellung->getBis());
	} ?>

    <h1>Materialbestellung</h1>

    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">

      <tr><td colspan="5">&nbsp;</td></tr><?php

	  if (isset($_SESSION["error"])) { ?>
        <tr><td colspan="5" class="error" align="center"><b><?php echo $_SESSION["error"]; ?></b></td></tr>
        <tr><td colspan="5">&nbsp;</td></tr> <?php 
      } 
      
      if (!isset($_GET['step']) || $_GET['step'] == 1){ ?>
        <form action="?seite=MaterialBestellen&action=DatumSpeichern&step=2" method="post">
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
                    <option value="<?php echo $einheit->getId(); ?>" <?php if($einheit->getId() == $bestellung->getEinheit()) { echo "selected='selected'"; } ?> ><?php echo $einheit->getBezeichnung(); ?></option> <?php 
                  } 
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
            <td alingn="right"><input type="submit" name="weiter" value="Weiter &raquo;" size="10"/></td>
          </tr> 

        </form> <?php

      } else if ($_GET['step'] == 2 ){ ?>
        <form action="?seite=MaterialBestellen&action=DatumAendern&step=1" method="post">
          <tr>
            <td colspan="5"><?php if ($_GET['step'] == 2 ){ ?><input type="submit" name="&Auml;ndern" value="&Auml;ndern" size="10"/><?php } ?></td>
          </tr>
        </form>

        <form action="?seite=MaterialBestellen&action=BestellungSpeichern&step=3" method="post">
          <tr>
            <td><b>Bestellung f&uuml;r:</b></td>
            <td><?php echo $bestellungenLog->Einheit($bestellung->getEinheit()); ?></td>
            <td></td>
            <td><b>Kontaktperson:</b></td>
            <td> <?php 
			  $kontakte = $bestellungenLog->Kontakte($bestellung->getEinheit());
			  if ($bestellung->getKontakt() == "") {
			    $kontaktperson = $_SESSION['benutzer']->getId();
			  } else {
			    $kontaktperson = $bestellung->getEinheit();
			  } ?>
              <select name="kontakt">  <?php
                $kontakte->index_setzen(-1);
                while (!$kontakte->listenende()) {
                  $benutzer = $kontakte->naechster_eintrag(); ?> 
                  <option value="<?php echo $benutzer->getId(); ?>" <?php if($benutzer->getId() == $kontaktperson) { echo "select='select'"; } ?>><?php
				    echo ucfirst($benutzer->getBenutzername()); ?></option> <?php
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
            <th colspan="3">Bezeichnung:</th>
            <th>Verf&uuml;gbar:</th>
            <th>Menge:</th>
          </tr> <?php

          $formulardaten->index_setzen(-1);

          while (!$formulardaten->listenende()) {
            $gruppe = $formulardaten->naechster_eintrag(); ?>
		    <tr>
              <th colspan="5">
			    <?php echo $gruppe->getBezeichnung(); ?>
                <input type="hidden" name="id" value="<?php echo $bestellung->getId(); ?>" />
                <input type="hidden" name="einheit" value="<?php echo $bestellung->getEinheit(); ?>" />
                <input type="hidden" name="von" value="<?php echo $bestellung->getVon(); ?>" />
                <input type="hidden" name="bis" value="<?php echo $bestellung->getBis(); ?>" />
                <input type="hidden" name="anlass" value="<?php echo $bestellung->getAnlass(); ?>" />
              </th>
            </tr> <?php	

            $artikelliste = $gruppe->getArtikel();
            $artikelliste->index_setzen(-1);

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
		    } ?>
            
            <tr><td colspan="5">&nbsp;</td></tr><?php
	      } ?>
            
          <tr><td colspan="5"><input type="submit" name="Speichern" value="Speichern" size="10"/></td></tr>

		</form><?php

	  } else if ($_GET['step'] >= 3 ){ ?>
        <tr>
          <td><b>Bestellung f&uuml;r:</b></td>
          <td><?php echo $bestellungenLog->Einheit($bestellung->getEinheit()); ?></td>
          <td></td>
          <td><b>Kontaktperson:</b></td>
          <td> <?php 
            $kontakte = $bestellungenLog->AlleKontakte();
            $kontakte->index_setzen(-1);
            while (!$kontakte->listenende()) {
              $benutzer = $kontakte->naechster_eintrag(); 
              if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
            } ?>
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
          <th colspan="4">Bezeichnung:</th>
          <th>Menge:</th>
        </tr> <?php

        $formulardaten->index_setzen(-1);

        while (!$formulardaten->listenende()) {
          $gruppe = $formulardaten->naechster_eintrag(); ?>
		  <tr><th colspan="5"><?php echo $gruppe->getBezeichnung(); ?></th></tr><?php		

		  $artikelliste = $gruppe->getArtikel();
          $artikelliste->index_setzen(-1);

          while (!$artikelliste->listenende()) {
            $artikel = $artikelliste->naechster_eintrag(); ?>
            <tr <?php if ($artikelliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
              <td colspan="4"><?php echo $artikel->getBezeichnung(); ?></td>
              <td align="right"><?php echo $artikel->getBestellmenge(); ?></td>
            </tr> <?php
		  } ?>
          
          <tr><td colspan="5">&nbsp;</td></tr><?php
	    }

		if ($_GET['step'] == 3 ){ ?>
          <tr>
            <td colspan="2">
              <form action="?seite=MaterialBestellen&action=BestellungBearbeiten&step=2" method="post"><input type="submit" name="Bearbeiten" value="Bearbeiten" size="10"/></form>
            </td>
            <td colspan="3" align="right">
              <form action="?seite=MaterialBestellen&action=BestellungAbsenden&step=4" method="post"><input type="submit" name="Absenden" value="Absenden" size="10"/></form>
            </td>
          </tr> <?php 
        } else if ($_GET['step'] == 4 ){ ?>
		  <tr>
            <td colspan="5" class="error">Mit dem Best&auml;tigen der Bestellung wird diese an das Material-Team versendet, und kann nicht mehr bearbeitet werden!</td>
          </tr>
          <tr>
            <td colspan="2">
              <form action="?seite=MaterialBestellen&step=3" method="post"><input type="submit" name="Abbrechen" value="Abbrechen" size="10"/></form>
            </td>
            <td colspan="3" align="right">
              <form action="?seite=MeineBestellungen&action=BestellungBestaetigen&id=<?php echo $bestellung->getId(); ?>" method="post"><input type="submit" name="Best&auml;tigen" value="Best&auml;tigen" size="10"/></form>
            </td>
          </tr> <?php 
        }
	  } ?>

    </table> <?php
  }


  function BestellungsVerwaltungListe($bestellungen){
    if ($bestellungen->anzahl_eintraege() >0) {
      switch($bestellungen->eintrag_mit_index(0)->getStatus()){
        case 0:   echo("<h1>Gespeicherte Materialbestellungen</h1>"); break; 
        case 1:   echo("<h1>Neue Materialbestellungen</h1>"); break; 
        case 2:   echo("<h1>Best&auml;tigte Materialbestellungen</h1>"); break; 
        case 3:   echo("<h1>Bereitgestellte Materialbestellungen</h1>"); break; 
        case 4:   echo("<h1>Abgeschlossene Materialbestellungen</h1>"); break; 
      }
    } else {
      echo("<h1>Materialbestellungen</h1>");
    } ?>

    <table id="mat">
      <col width="100">
      <col width="150">
      <col width="100">
      <col width="100">
      <col width="60">
      <col width="150">

      <tr>
        <th><b>Einheit:</b></th>
        <th><b>Anlass:</b></th>
        <th><b>Vom:</b></th>
        <th><b>Bis:</b></th>
        <th><b>Status:</b></th>
        <th></th>
      </tr><?php

	  $bestellungen->index_setzen(-1);
	  $i=0;

      while (!$bestellungen->listenende()) { 
	    $bestellung = $bestellungen->naechster_eintrag(); ?>
        <tr <?php if ($i++%2 != 0) { echo "id='uneven'"; } ?>>
          <td><?php echo $bestellung->getEinheitBez(); ?></td>
          <td><?php echo $bestellung->getAnlass(); ?></td>
          <td><?php echo date("d.m.Y", $bestellung->getVon()); ?></td>
          <td><?php echo date("d.m.Y", $bestellung->getBis()); ?></td>
          <td><?php 
            switch($bestellung->getStatus()){
              case "0": ?>
                <img src="img/rot.png" width="10" height="10" alt="gespeichert"/><?php
                break;
              case "1": ?>
                <img src="img/grau.png" width="10" height="10" alt="eingereicht"/><?php
                break;
              case "2": ?>
                <img src="img/gelb.png" width="10" height="10" alt="bestaetigt"/><?php
                break;
              case "3": ?>
                <img src="img/gruen.png" width="10" height="10" alt="bereitgestellt"/><?php
                break;
              case "4": ?>
                <img src="img/dunkelgruen.png" width="10" height="10" alt="abgeschlossen"/><?php
                break;
            }?>
          </td>
          <td><?php 
            switch($bestellung->getStatus()){
              case "0": ?>
                <a href="?seite=BestellungAnzeigen&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/anzeigen-suchen.png" width="10" height="10" border="0"/></a>
              
              
              <?php
                break;
              case "1": ?>
                <a href="?seite=BestellungBestaetigen&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/anzeigen-suchen.png" width="10" height="10" border="0"/></a>
                <a href="?seite=BestellungLoeschen&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a>  
                  
                  <?php
                break;
              case "2": ?>
                <a href="?seite=BestellungBereitstellen&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/anzeigen-suchen.png" width="10" height="10" border="0"/></a>
                <a href="output/Ausgabeformular.php?bestellung=<?php echo $bestellung->getId(); ?>" target="_blank"><img src="img/drucken.png" width="10" height="10" border="0"/></a>    
                <a href="?seite=BestellungLoeschen&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a>  
                    <?php
                break;
              case "3": ?>
                <a href="?seite=BestellungRueckgabe&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/anzeigen-suchen.png" width="10" height="10" border="0"/></a>
                <a href="output/Rueckgabeformular.php?bestellung=<?php echo $bestellung->getId(); ?>" target="_blank"><img src="img/drucken.png" width="10" height="10" border="0" alt="R&uuml;ckgabe"/></a>
                <a href="output/RueckgabeOffen.php?bestellung=<?php echo $bestellung->getId(); ?>" target="_blank"><img src="img/drucken.png" width="10" height="10" border="0" alt="Offen"/></a>
                  <?php
                break;
              case "4": ?>
                <a href="?seite=BestellungAbgeschlossen&index=<?php echo $bestellungen->aktueller_index(); ?>"><img src="img/anzeigen-suchen.png" width="10" height="10" border="0"/></a>
              
               <?php
                break;
            } ?>
                    
          </td>
        </tr> <?php 
      } ?>
    </table>

    <!--p>&nbsp;</p>
    
    <table>
      <col width="12">
      <col width="90">
      <col width="12">
      <col width="90">
      <col width="12">
      <col width="90">
      <col width="12">
      <col width="90">
      <col width="12">
      <col width="90">

      <tr><td colspan="10"><b>Status:</b></td></tr>
      <tr>
        <td><img src="img/rot.png" width="10" height="10" alt="gespeichert"/></td>
        <td>gespeichert</td>
        <td><img src="img/grau.png" width="10" height="10" alt="eingereicht"/></td>
        <td>eingereicht</td>
        <td><img src="img/gelb.png" width="10" height="10" alt="angenommen"/></td>
        <td>angenommen</td>
        <td><img src="img/gruen.png" width="10" height="10" alt="bereitgestellt"/></td>
        <td>bereitgestellt</td>
        <td><img src="img/dunkelgruen.png" width="10" height="10" alt="abgeschlossen"/></td>
        <td>abgeschlossen</td>
      </tr>
    </table--> <?php 
  } 


  function BestellungAnzeigenVerwalten($bestellung){
	$bestellungenLog = new BestellungenLog(); ?>

    <h1>Materialbestellung</h1>
    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">

      <tr><td colspan="5">&nbsp;</td></tr>

      <tr>
        <td><b>Bestellung f&uuml;r:</b></td>
        <td><?php echo $bestellung->getEinheitBez(); ?></td>
        <td></td>
        <td><b>Kontaktperson:</b></td>
        <td> <?php 
          $kontakte = $bestellungenLog->AlleKontakte();
          $kontakte->index_setzen(-1);
          while (!$kontakte->listenende()) {
            $benutzer = $kontakte->naechster_eintrag(); 
            if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
          } ?>
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
        <th colspan="4">Bezeichnung:</th>
        <th>Menge:</th>
      </tr> <?php

  	  $materialliste = $bestellung->getMatbestellung();
      $materialliste->index_setzen(-1);

      while (!$materialliste->listenende()) {
        $material = $materialliste->naechster_eintrag(); ?>
        <tr <?php if ($materialliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
          <td colspan="4"><?php echo $material->getBezeichnung(); ?></td>
          <td align="right"><?php echo $material->getBestellmenge(); ?></td>
        </tr> <?php
      } ?>
    
      <tr><td colspan="5">&nbsp;</td></tr>
    </table><?php

    $seite = strstr($_SERVER['HTTP_REFERER'], "seite=");

    if (strpos($seite, "&") !== false) {
      $seite = strstr($seite, "&", true);
    }

    if ($bestellung->getStatus()>0){
      switch($bestellung->getStatus()-1){
        case "0": ?>
          <a href="?<?php echo $seite; ?>&action=StatusSetzen&id=<?php echo $bestellung->getId(); ?>&status=<?php echo ($bestellung->getStatus()-1); ?>" id="link">
            <img src="img/rot.png" width="10" height="10" alt="gespeichert"/> Bestellung an Einheit zur&uuml;ckweisen
          </a><br/><?php
          break;
        case "1": ?>
          <a href="?<?php echo $seite; ?>&action=StatusSetzen&id=<?php echo $bestellung->getId(); ?>&status=<?php echo ($bestellung->getStatus()-2); ?>" id="link">
            <img src="img/rot.png" width="10" height="10" alt="gespeichert"/> Bestellung an Einheit zur&uuml;ckweisen
          </a><br/><?php 
          break;
        case "2": ?>
          <a href="?<?php echo $seite; ?>&action=StatusSetzen&id=<?php echo $bestellung->getId(); ?>&status=<?php echo ($bestellung->getStatus()-1); ?>" id="link">
            <img src="img/gelb.png" width="10" height="10" alt="angenommen"/> Material nicht Bereitgestellt (&Auml;nderung an der Bestellung)
          </a><br/><?php
          break;
        case "3": ?>
          <a href="?<?php echo $seite; ?>&action=StatusSetzen&id=<?php echo $bestellung->getId(); ?>&status=<?php echo ($bestellung->getStatus()-1); ?>" id="link">
            <img src="img/gruen.png" width="10" height="10" alt="bereitgestellt"/> Bestellung nicht Abgeschlossen
          </a><br/><?php
          break;
      }
    }

    if ($bestellung->getStatus()<4){
      switch($bestellung->getStatus()+1){
        case "1": ?>
          <a href="?<?php echo $seite; ?>&action=StatusSetzen&id=<?php echo $bestellung->getId(); ?>&status=<?php echo ($bestellung->getStatus()+1); ?>" id="link">
            <img src="img/grau.png" width="10" height="10" alt="eingereicht"/> Materialbestellung einreichen</a>
          <br/><?php
          break;
        case "2": ?>
          <a href="?<?php echo $seite; ?>&action=StatusSetzen&id=<?php echo $bestellung->getId(); ?>&status=<?php echo ($bestellung->getStatus()+1); ?>" id="link">
            <img src="img/gelb.png" width="10" height="10" alt="angenommen"/> Materialbestellung annehmen
          </a><br/><?php
          break;
        case "3": ?>
          <a href="?<?php echo $seite; ?>&action=StatusSetzen&id=<?php echo $bestellung->getId(); ?>&status=<?php echo ($bestellung->getStatus()+1); ?>" id="link">
            <img src="img/gruen.png" width="10" height="10" alt="bereitgestellt"/> Material ist bereitgestellt
          </a><br/><?php
          break;
        case "4": ?>
          <a href="?<?php echo $seite; ?>&action=StatusSetzen&id=<?php echo $bestellung->getId(); ?>&status=<?php echo ($bestellung->getStatus()+1); ?>" id="link">
            <img src="img/dunkelgruen.png" width="10" height="10" alt="abgeschlossen"/> Materialr&uuml;ckgabe erfolgt und Bestellung abgeschlossen
          </a><br/><?php
          break;
      }
    } ?>

	<p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">&laquo; Zur&uuml;ck</a></p> <?php
  }
    
  /**************************************************************************************************************
   * Verwaltung der Bestellungen
   **************************************************************************************************************/
    
  function BestellungBestaetigen($bestellung){
	$bestellungenLog = new BestellungenLog(); ?>

    <h1>Materialbestellung best&auml;tigen</h1>
    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">

      <tr><td colspan="5">&nbsp;</td></tr>

      <tr>
        <td><b>Bestellung f&uuml;r:</b></td>
        <td><?php echo $bestellung->getEinheitBez(); ?></td>
        <td></td>
        <td><b>Kontaktperson:</b></td>
        <td> <?php 
          $kontakte = $bestellungenLog->AlleKontakte();
          $kontakte->index_setzen(-1);
          while (!$kontakte->listenende()) {
            $benutzer = $kontakte->naechster_eintrag(); 
            if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
          } ?>
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
    </table>
    
    <p>&nbsp;</p>

    <form action="?seite=Neu&action=BestellungBestaetigen" method="post">
      <table id="mat">
        <col width="315">
        <col width="75">
        <col width="75">
        <col width="75">

        <tr>
          <th>Bezeichnung:<input type="hidden" name="bestellung" value="<?php echo $bestellung->getId(); ?>"></th>
          <th>Bestellt:</th>
          <th>Best&auml;tigt:</th>
          <th>Verfügbar:</th>
        </tr> <?php

  	    $materialliste = $bestellung->getMatbestellung();
        $materialliste->index_setzen(-1);

        while (!$materialliste->listenende()) {
          $material = $materialliste->naechster_eintrag(); ?>
          <tr <?php if ($materialliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
            <td><?php echo $material->getBezeichnung(); ?><input type="hidden" name="matid[]" value="<?php echo $material->getId(); ?>"></td>
            <td align="right"><?php echo $material->getBestellmenge(); ?></td>
            <td align="right">
              <input type="number" name="menge[]"  size="10" maxlength="3" min="0" max="<?php echo $material->getBestand()-$material->getAlltagsbestand()-$material->getAusgeliehen(); ?>" 
                    value="<?php echo $material->getBestellmenge(); ?>" step="<?php echo $material->getPackgroesse(); ?>" align="right"/>
            </td>
            <td align="right"><?php echo $material->getBestand()-$material->getAlltagsbestand()-$material->getAusgeliehen(); ?></td>
          </tr> <?php
        } ?>
          
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4"><input type="submit" name="bestaetigen" value="Bestellung bestätigen"> <input type="submit" name="zurueckweisen" value="Bestellung an Einheit zurückweisen"></td></tr>
          
      </table>
    </form><?php
  }   
    
  function BestellungBereitstellen($bestellung, $hinzufuegen){
	$bestellungenLog = new BestellungenLog(); ?>

    <h1>Materialbestellung</h1>
    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">

      <tr><td colspan="5">&nbsp;</td></tr>

      <tr>
        <td><b>Bestellung f&uuml;r:</b></td>
        <td><?php echo $bestellung->getEinheitBez(); ?></td>
        <td></td>
        <td><b>Kontaktperson:</b></td>
        <td> <?php 
          $kontakte = $bestellungenLog->AlleKontakte();
          $kontakte->index_setzen(-1);
          while (!$kontakte->listenende()) {
            $benutzer = $kontakte->naechster_eintrag(); 
            if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
          } ?>
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
    </table>
    
    <p>&nbsp;</p>

    <form action="?seite=BestellungenVerwalten&action=MaterialBereitstellen&index=<?php echo $_GET['index']; ?>" method="post">
      <table id="mat">
        <col width="315">
        <col width="75">
        <col width="75">
        <col width="75">

        <tr>
          <th colspan="2">Bezeichnung:<input type="hidden" name="bestellung" value="<?php echo $bestellung->getId(); ?>"></th>
          <th>Best&auml;tigt:</th>
          <th>Bereit:</th>
        </tr> <?php

  	    $materialliste = $bestellung->getMatbestellung();
        $materialliste->index_setzen(-1);

        while (!$materialliste->listenende()) {
          $material = $materialliste->naechster_eintrag(); ?>
          <tr <?php if ($materialliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
            <td colspan="2"><?php echo $material->getBezeichnung(); ?><input type="hidden" name="matid[]" value="<?php echo $material->getId(); ?>"></td>
            <td align="right"><?php echo $material->getBestaetigtemenge(); ?></td>
            <td align="right"><input type="number" name="menge[]"  size="10" maxlength="3" min="0" max="<?php echo $material->getBestand()-$material->getAlltagsbestand()-$material->getAusgeliehen(); ?>" 
                    value="<?php echo $material->getAusgabemenge(); ?>" step="<?php echo $material->getPackgroesse(); ?>" align="right"/>
                    <!--input type="text" name="menge[]" size="10" value="<?php echo $material->getAusgabemenge(); ?>"--></td>
          </tr> <?php
        } 
          
        if ($hinzufuegen) { ?>
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"><b>Zus&auml;tzliches Material</b></td>
          </tr>
          <tr>
            <th colspan="3">Bezeichnung:<input type="hidden" name="bestellung" value="<?php echo $bestellung->getId(); ?>"></th>
            <th>Bereit:</th>
          </tr> <?php
          
          $mathinzufuegen = $_SESSION['mathinzufuegen'];              
          while (!$mathinzufuegen->listenende()) {
            $material = $mathinzufuegen->naechster_eintrag(); ?>
            <tr <?php if ($mathinzufuegen->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
              <td colspan="3"><?php echo $material->getBezeichnung(); ?><input type="hidden" name="n_matid[]" value="<?php echo $material->getId(); ?>"></td>
              <td align="right">
                  <input type="number" name="n_menge[]"  size="10" maxlength="3" min="0" max="<?php echo $material->getBestand()-$material->getAlltagsbestand()-$material->getAusgeliehen(); ?>" 
                    value="<?php echo $material->getAusgabemenge(); ?>" step="<?php echo $material->getPackgroesse(); ?>" align="right"/>
                  <!--input type="text" name="n_menge[]" size="10" value="<?php echo $material->getAusgabemenge(); ?>"-->
                </td>
            </tr> <?php
          } 
          
          
          
          
          
          
        } else { ?>
          
          <tr><td colspan="4">
            <img src="img/neu.png" width="10" height="10" alt="Artikel hinzuf&uuml;gen"/> Artikel hinzuf&uuml;gen
            <input type="submit" name="neu" value="+ Artikel hinzuf&uuml;gen"></td>
          </tr> <?php
        } ?>
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4"><input type="submit" name="bereitgestellt" value="Material bereitgestellt"> <input type="submit" name="zuruecksetzen" value="Best&auml;tigung wiederrufen"></td></tr>
          
      </table>
    </form>

	<p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">&laquo; Zur&uuml;ck</a></p> <?php
  }
    
  function BestellungRueckgabe($bestellung){
	$bestellungenLog = new BestellungenLog(); ?>

    <h1>Materialbestellung r&uuml;ckgabe</h1>
    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">

      <tr><td colspan="5">&nbsp;</td></tr>

      <tr>
        <td><b>Bestellung f&uuml;r:</b></td>
        <td><?php echo $bestellung->getEinheitBez(); ?></td>
        <td></td>
        <td><b>Kontaktperson:</b></td>
        <td> <?php 
          $kontakte = $bestellungenLog->AlleKontakte();
          $kontakte->index_setzen(-1);
          while (!$kontakte->listenende()) {
            $benutzer = $kontakte->naechster_eintrag(); 
            if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
          } ?>
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
    </table>
    
    <p>&nbsp;</p>

    <form action="?seite=Neu&action=BestellungRueckgabe" method="post">
      <table id="mat">
        <col width="315">
        <col width="80">
        <col width="80">
        <col width="80">
        <col width="80">
        <col width="80">

        <tr>
          <th>Bezeichnung:<input type="hidden" name="bestellung" value="<?php echo $bestellung->getId(); ?>"></th>
          <th>Ausgegeben:</th>
          <th>Zur&uuml;ck:</th>
          <th>davon Rep:</th>
          <th>Defekt:</th>
          <th>noch Offen:</th>
        </tr> <?php

  	    $materialliste = $bestellung->getMatbestellung();
        $materialliste->index_setzen(-1);

        while (!$materialliste->listenende()) {
          $material = $materialliste->naechster_eintrag(); ?>
          <tr <?php if ($materialliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
            <td><?php echo $material->getBezeichnung(); ?><input type="hidden" name="matid[]" value="<?php echo $material->getId(); ?>"></td>
            <td align="right"><?php echo $material->getAusgabemenge(); ?></td>
            <?php if  ($material->getAusgabemenge()>0) { ?>
              
              
              <td align="right">
                <input type="number" name="menge[]"  size="10" maxlength="3" min="0" max="<?php echo $material->getAusgabemenge(); ?>" 
                    value="<?php echo $material->getRueckgabemenge(); ?>" step="1" align="right"/>
              </td>
              <?php if ($material->getVerbrauchsmaterial()) { ?>
                <td align="right"><input type="hidden" name="rep[]" value="0" />  </td>
                <td align="right"><input type="hidden" name="def[]" value="0" /></td>
              <?php } else { ?>
                <td align="right"><input type="number" name="rep[]"  size="10" maxlength="3" min="0" max="<?php echo $material->getAusgabemenge(); ?>" 
                    value="<?php echo $material->getRueckgabeReparatur(); ?>" step="1" align="right"/></td>
                <td align="right"><input type="number" name="def[]"  size="10" maxlength="3" min="0" max="<?php echo $material->getAusgabemenge(); ?>" 
                    value="<?php echo $material->getRueckgabeDefekt(); ?>" step="1" align="right"/></td>              
              <?php } ?>
              

              
            <?php } else { ?>
              <td align="right"><input type="hidden" name="menge[]" value="0" /></td>
              <td align="right"><input type="hidden" name="rep[]" value="0" /></td>
              <td align="right"><input type="hidden" name="def[]" value="0" /></td>
            <?php } ?>
              
            <td align="right"><?php 
              if (!$material->getVerbrauchsmaterial()) { 
                echo ($material->getAusgabemenge()-$material->getRueckgabemenge()-$material->getRueckgabeReparatur()-$material->getRueckgabeDefekt()); 
              } ?>
            </td>  
              
              
          </tr> <?php
        } ?>
          
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4"><input type="submit" name="bestaetigen" value="R&uuml;ckgabe best&auml;tigen"> <input type="submit" name="speichern" value="R&uuml;ckgabe zwischenspeichern">
            <input type="submit" name="zuruecksetzen" value="Bereitstellung zur&uuml;cksetzen"></td></tr>
          
      </table>
    </form>

    <p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">&laquo; Zur&uuml;ck</a></p><?php
  }   
    
  function BestellungAbgeschlossen($bestellung){
	$bestellungenLog = new BestellungenLog(); ?>

    <h1>Materialbestellung best&auml;tigen</h1>
    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">

      <tr><td colspan="5">&nbsp;</td></tr>

      <tr>
        <td><b>Bestellung f&uuml;r:</b></td>
        <td><?php echo $bestellung->getEinheitBez(); ?></td>
        <td></td>
        <td><b>Kontaktperson:</b></td>
        <td> <?php 
          $kontakte = $bestellungenLog->AlleKontakte();
          $kontakte->index_setzen(-1);
          while (!$kontakte->listenende()) {
            $benutzer = $kontakte->naechster_eintrag(); 
            if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
          } ?>
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
    </table>
    
    <p>&nbsp;</p>

    <form action="?seite=Neu&action=BestellungAbgeschlossen" method="post">
      <table id="mat">
        <col width="315">
        <col width="75">
        <col width="75">
        <col width="75">

        <tr>
          <th>Bezeichnung:<input type="hidden" name="bestellung" value="<?php echo $bestellung->getId(); ?>"></th>
          <th>Bestellt:</th>
          <th>Bereit:</th>
          <th>Rückgabe:</th>
        </tr> <?php

  	    $materialliste = $bestellung->getMatbestellung();
        $materialliste->index_setzen(-1);

        while (!$materialliste->listenende()) {
          $material = $materialliste->naechster_eintrag(); ?>
          <tr <?php if ($materialliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
            <td><?php echo $material->getBezeichnung(); ?></td>
            <td align="right"><?php echo $material->getBestellmenge(); ?></td>
            <td align="right"><?php echo $material->getAusgabemenge(); ?></td>
            <td align="right"><?php echo $material->getRueckgabemenge(); ?></td>
          </tr> <?php
        } ?>
          
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4"><input type="submit" name="zuruecksetzen" value="Material-R&uuml;ckgabe zur&uuml;cksetzen"></td></tr>
          
      </table>
    </form>

    <p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">&laquo; Zur&uuml;ck</a></p><?php
  }     
    
    
    
    
    
/*************************************************************************************************************************************
 * Allgemein gültige Funktionen                                                                                                      *
 *************************************************************************************************************************************/

  function BestellungLoeschen($bestellung) { 
    $bestellungenLog = new BestellungenLog(); ?>
    <h1>Materialbestellung l&ouml;schen</h1>

    <table id="mat">
      <col width="120">
      <col width="100">
      <col width="100">
      <col width="120">
      <col width="100">

      <tr><td colspan="5">&nbsp;</td></tr>
      <tr>
        <td><b>Bestellung f&uuml;r:</b></td>
        <td><?php echo $bestellung->getEinheitBez(); ?></td>
        <td></td>
        <td><b>Kontaktperson:</b></td>
        <td> <?php 
          $kontakte = $bestellungenLog->AlleKontakte();
          $kontakte->index_setzen(-1);
          while (!$kontakte->listenende()) {
            $benutzer = $kontakte->naechster_eintrag(); 
            if($benutzer->getId() == $bestellung->getKontakt()) { echo ucfirst($benutzer->getBenutzername()); } 
          }?>
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
        <th colspan="4">Bezeichnung:</th>
        <th>Menge:</th>
      </tr> <?php

  	  $materialliste = $bestellung->getMatbestellung();
      $materialliste->index_setzen(-1);
      while (!$materialliste->listenende()) {
        $material = $materialliste->naechster_eintrag(); ?>
        <tr <?php if ($materialliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
          <td colspan="4"><?php echo $material->getBezeichnung(); ?></td>
          <td align="right"><?php echo $material->getBestellmenge(); ?></td>
        </tr> <?php
      } ?>

      <tr><td colspan="5">&nbsp;</td></tr>

	  <tr>
        <td colspan="5" class="error">Mit dem Best&auml;tigen wird diese Bestellung unwiederruflich gel&ouml;scht, und kann nicht mehr bearbeitet werden!</td>
      </tr>

      <tr>
        <td colspan="2">
          <form action="?seite=MeineBestellungen" method="post"><input type="submit" name="Abbrechen" value="Abbrechen" size="10"/></form>
        </td>
        <td colspan="3" align="right">
          <form action="?seite=MeineBestellungen&action=BestellungLoeschen&id=<?php echo $bestellung->getId(); ?>" method="post"><input type="submit" name="L&ouml;schen" value="L&ouml;schen" size="10"/></form>
        </td>
      </tr>
    </table> <?php
  }
}
?>