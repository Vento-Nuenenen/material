<?php
include_once("BenutzerLog.php");

class BenutzerGui{

/*************************************************************************************************************************************
 * Funktionen                                                                                                       *
 *************************************************************************************************************************************/

  function BenutzerAnmeldung() {
    ?>
    <form action="index.php?action=Anmelden" method="post">
      <p>&nbsp;</p>
      <table>
        <col width="100">
        <tr>
          <td>Benutzername:</td>
          <td><input type="text" name="benutzername" size="30" maxlength="50"/></td>
        </tr>
        <tr>
          <td>Passwort:</td>
          <td><input type="password" name="passwort" size="30" maxlength="50"/></td>
        </tr>
        <tr>
          <td colspan="8"><input type="submit" name="Anmelden" value="Anmelden" /></td>
        </tr>
      </table>
    </form>
    <p><a href="?seite=PasswortVergessen" >Passwort vergessen</a></p>
    <?
  }

  function PasswortVergessen() {
    ?>
    <form action="index.php?action=PasswortZuruecksetzen" method="post">
      <p>&nbsp;</p>
      <table>
        <col width="250">
        <tr>
          <td>Benutzername oder eMail-Adresse:</td>
          <td><input type="text" name="benutzername" size="30" maxlength="60"/></td>
        </tr>
        <tr>
          <td colspan="8"><input type="submit" name="Zur&uuml;cksetzen" value="Zur&uuml;cksetzen" /><input type="submit" name="Abbrechen" value="Abbrechen" /></td>
        </tr>
      </table>
    </form>
    <?
  }

  function PasswortAendern($benutzerId) {
    ?>
    <form action="index.php?action=PasswortSpeichern" method="post">
      <p>&nbsp;</p>
      <table>
        <col width="250">
        <tr>
          <td>altes Passwort:<input type="hidden" name="id" value="<?php echo $benutzerId; ?>" /></td>
          <td><input type="password" name="alt" size="30" maxlength="60"/></td>
        </tr>
        <tr>
          <td>neues Passwort:</td>
          <td><input type="password" name="neu1" size="30" maxlength="60"/></td>
        </tr>
        <tr>
          <td>neues Passwort wiederhohlen:</td>
          <td><input type="password" name="neu2" size="30" maxlength="60"/></td>
        </tr>
        <tr>
          <td colspan="8"><input type="submit" name="Speichern" value="Speichern" /></td>
        </tr>
      </table>
    </form>
    <?
  }

  function BenutzerlisteAnzeigen($benutzerliste) {
    ?>
    <h1>Benutzer</h1>
    <table id="mat">
      <col width="110">
      <col width="100">
      <col width="100">
      <col width="100">
      <col width="150">
      <col width="50">
      <col width="60">
      <?php 
      if (isset($_GET['index'])){
        $benutzer = $benutzerliste->eintrag_mit_index($_GET['index']);
      } else {
        $benutzer = new Benutzer();
      }
      
      if ($_GET['action'] == "loeschen") { ?>
        <form action="?seite=Benutzer&action=BenutzerLoeschen" method="post">
          <tr>
            <td><input type="hidden" name="id" value="<?php echo $benutzer->getId(); ?>" /><?php echo $benutzer->getBenutzername(); ?></td>
            <td><?php echo $benutzer->getName(); ?></td>
            <td><?php echo $benutzer->getVorname(); ?></td>
            <td><?php echo $benutzer->getPfadiname(); ?></td>
            <td><?php echo $benutzer->getEmail(); ?></td>
            <td align="center"><?php if($benutzer->getAktiv()){ ?><img src="img/gruen.png" width="10" height="10" border="0"/><?php } else { ?>
                             <img src="img/rot.png" width="10" height="10" border="0"/><?php } ?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="7"><input type="submit" name="Löschen" value="Löschen" /><input type="submit" name="Abbrechen" value="Abbrechen" /></td>
          </tr>
        </form>
      <?php } ?>
      <tr><td colspan="7">&nbsp;</td></tr>
      <tr>
        <th>Benutzername:</th>
        <th>Name:</th>
        <th>Vorname:</th>
        <th>Pfadiname:</th>
        <th>eMail:</th>
        <th>Aktiv:</th>
        <th><a href="?seite=BenutzerErfassen"><img src="img/neu.png" width="10" height="10" border="0"/></a></th>
      </tr>
	  <?php
	  $benutzerliste->index_setzen(-1);
      while (!$benutzerliste->listenende()) {
        $benutzer = $benutzerliste->naechster_eintrag(); ?>
        <tr <?php if ($benutzerliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
          <td><?php echo $benutzer->getBenutzername(); ?></td>
          <td><?php echo $benutzer->getName(); ?></td>
          <td><?php echo $benutzer->getVorname(); ?></td>
          <td><?php echo $benutzer->getPfadiname(); ?></td>
          <td><?php echo $benutzer->geteMail(); ?></td>
          <td align="center"><?php if($benutzer->getAktiv()){ ?><img src="img/gruen.png" width="10" height="10" border="0"/><?php } else { ?>
                             <img src="img/rot.png" width="10" height="10" border="0"/><?php } ?></td>
          <td>
            <a href="?seite=BenutzerBearbeiten&index=<?php echo $benutzerliste->aktueller_index(); ?>"><img src="img/bearbeiten.png" width="10" height="10" border="0"/></a>
            <a href="?seite=Benutzer&action=BenutzerEinladen&id=<?php echo $benutzer->getId(); ?>"><img src="img/mail.png" width="10" height="10" border="0"/></a>
            <?php 
			$benutzerLog = new BenutzerLog();
			if($benutzerLog->LoeschenPruefen($benutzer->getId())){ ?>
            <a href="?seite=Benutzer&action=loeschen&index=<?php echo $benutzerliste->aktueller_index(); ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
    </table>
    <?
      
  }

  function BenutzerBearbeiten($benutzer) {
    $benutzerLog = new BenutzerLog();
    if($benutzer->getId() != "") { ?>
      <h1>Benutzer bearbeiten</h1>
    <?php } else { ?>
      <h1>Benutzer erfassen</h1>
    <?php } ?>
    <form action="?seite=Benutzer&action=BenutzerSpeichern" method="post">
      <input name="id" type="hidden" value="<?php echo $benutzer->getId(); ?>" />
	  <table>
        <colgroup>
          <col width="150">
          <col width="200">
          <col width="20">
          <col width="150">
          <col width="200">
        </colgroup> <?php
        if($benutzer->getId() != "") { ?>
          <tr valign="top">
            <td height="15"><strong>Benutzername</strong></td>
            <td><?php echo $benutzer->getBenutzername(); ?></td>
            <td><input type="hidden" name="benutzername" value="<?php echo $benutzer->getBenutzername(); ?>"></td>
            <td></td>
            <td></td>
          </tr><?php
        } ?>
        <tr>
          <td><strong>Name</strong></td>
          <td><input type="text" name="name" value="<?php  echo $benutzer->getName() ; ?>" size="25"></td>  
          <td></td>
          <td><strong>Vorname</strong></td>
          <td><input type="text" name="vorname" value="<?php echo $benutzer->getVorname(); ?>" size="25"></td>
        </tr>
        <tr>
          <td><strong>Pfadiname</strong></td>
          <td><input type="text" name="pfadiname" value="<?php  echo $benutzer->getPfadiname() ; ?>" size="25"></td>  
          <td></td>
          <td height="15"><strong>Aktiv</strong></td>
          <td>
            <input name="aktiv" type="radio" value="1" <?php if ($benutzer->getAktiv()) { echo "checked='checked'";} ?>/> Ja &nbsp; | &nbsp; 
            <input name="aktiv" type="radio" value="0" <?php if (!$benutzer->getAktiv()) { echo "checked='checked'";} ?>/> Nein 
          </td>
        </tr>
        <tr>
          <td><strong>eMail</strong></td>
          <td><input type="text" name="email" value="<?php  echo $benutzer->getEmail() ; ?>" size="25"></td>  
          <td></td>
          <td height="15"><strong></strong></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr valign="top">
          <td><strong>Zugewiesene Rollen</strong><br />Mehfachauswahl mit "CTRL"</td>
          <td>
            <?php 
            $rollen = $benutzerLog->RollenListe();
            /*$rollen_user = $benutzer->getBerechtigungen();
            $zugewiesen = array();
            for ($i=0; $i<$rollen_user->anzahl_eintraege(); $i++) {
              $zugewiesen[] = $rollen_user->eintrag_mit_index($i)->get_id();
            }*/
            ?>
            <select name="rollen[]" multiple size="<?php echo $rollen->anzahl_eintraege(); ?>" class="select">
              <?php for ($i=0; $i<$rollen->anzahl_eintraege(); $i++) { 
                $rolle = $rollen->eintrag_mit_index($i); ?>
                <option value="<?php echo $rolle->getId(); ?>" <?php if ($benutzerLog->RolleBenutzer($rolle->getID(), $benutzer->getId())) { echo "selected='selected'"; } ?>>
                  <?php echo $rolle->getBezeichnung(); ?>
                </option>
              <?php } ?>
            </select>
          </td> 
          <td></td>
          <td></td>
          <td></td> 
        </tr>
        <tr>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5">
            <input type="submit" name="Speichern"  value="Speichern" />
            <input type="submit" name="Abbrechen" value="Abbrechen" />
          </td>
        </tr>
      </table>
	 </form>
    <?php
  }

/****************************************************************************************************************
 * Rollen                                                                                              *
 ****************************************************************************************************************/
  
  function RollenlisteAnzeigen($rollenliste) { 
    ?>
    <h1>Benutzerrollen</h1>
    <table id="mat">
      <col width="150">
      <col width="300">
      <col width="200">
      <col width="60">
      <tr><td colspan="7">&nbsp;</td></tr>
      <tr>
        <th>Bezeichnung:</th>
        <th>Beschreibung:</th>
        <th>Benutzer:</th>
        <th align="right"><a href="?seite=RolleErfassen"><img src="img/neu.png" width="10" height="10" border="0"/></a></th>
      </tr>
	  <?php
	  $rollenliste->index_setzen(-1);
      while (!$rollenliste->listenende()) {
        $rolle = $rollenliste->naechster_eintrag(); ?>
        <tr <?php if ($rollenliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
          <td><?php echo $rolle->getBezeichnung(); ?></td>
          <td><?php echo $rolle->getBeschreibung(); ?></td>
          <td><?php echo $rolle->getBenutzer(); ?></td>
          <td>
            <a href="?seite=RolleBearbeiten&index=<?php echo $rollenliste->aktueller_index(); ?>"><img src="img/bearbeiten.png" width="10" height="10" border="0"/></a>
            <a href="?seite=RolleLoeschen&index=<?php echo $rollenliste->aktueller_index(); ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a>
          </td>
        </tr>
      <?php } ?>
    </table>
    <? 
      
  }

  function RolleBearbeiten($rolle) {
    if ($rolle->getId() != NULL){
	  echo "<h1>Rolle bearbeiten</h1> ";	
	} else {
	  echo "<h1>Rolle erfassen</h1> ";	
	}
    
    ?>
    <form action="?seite=Rollen&action=RolleSpeichern" method="post">
      <table>
        <colgroup>
          <col width="150">
          <col width="600">
        </colgroup>
        <tr>
          <td>Bezeichnung:<input name="id" type="hidden" value="<?php echo $rolle->getId(); ?>" /></td>
          <td><input name="bezeichnung" type="text" value="<?php echo $rolle->getBezeichnung(); ?>"/></td>
        </tr>
        <tr valign="top">
          <td>Beschreibung:</td>
          <td><textarea name="beschreibung" cols="60" rows="3"><?php echo $rolle->getBeschreibung(); ?></textarea></td>
        </tr>
        <tr>
          <td>aktuelle Benutzer:</td>
          <td><?php echo $rolle->getBenutzer(); ?></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <table id="mat">
        <colgroup>
          <col width="150">
          <col width="100">
          <col width="100">
          <col width="100">
        </colgroup>
        <tr>
          <th>Applikationsteil</th>
          <th>Lesen</th>
          <th>Schreiben</th>
          <th>Löschen</th>
        </tr>
        <?php 
	    $berechtigungen = $rolle->getBerechtigungen();
        for ($i = 0; $i < $berechtigungen->anzahl_eintraege(); $i++){
	    $applikationsteil = $berechtigungen->eintrag_mit_index($i)
            ?>
            <tr>
              <td><b><i><?php echo $applikationsteil->getBezeichnung(); ?></i></b></td>
              <td align="center">
                <input name="lesen_<?php echo $applikationsteil->getId() ?>" type="checkbox" value="1" 
                  <?php if ($applikationsteil->getLesen()) { echo "checked='checked'"; }?> />
              </td>
              <td align="center">
                <input name="schreiben_<?php echo $applikationsteil->getId() ?>" type="checkbox" value="1" 
                  <?php if ($applikationsteil->getSchreiben()) { echo "checked='checked'"; }?> />
              </td>
              <td align="center">
                <input name="loeschen_<?php echo $applikationsteil->getId() ?>" type="checkbox" value="1" 
                  <?php if ($applikationsteil->getLoeschen()) { echo "checked='checked'"; }?> />
              </td>
            </tr>
          <?php } 
          $benutzerLog = new BenutzerLog()?>
          <tr><td colspan="4">&nbsp;</td></tr>
          <tr valign="top">
            <td colspan="2"><strong>Bestellberechtigungen gültig für:</strong><br />Mehfachauswahl mit "CTRL"</td>
            <td colspan="2">  <?php 
              $einheiten = $benutzerLog->EinheitenListe();
              if ($rolle->getEinheiten() != NULL) {
                $rollen_einh = $rolle->getEinheiten();
              } else {
                $rollen_einh = new Liste();  
              }
              $zugewiesen = array();
              for ($i=0; $i<$rollen_einh->anzahl_eintraege(); $i++) {
                $zugewiesen[] = $rollen_einh->eintrag_mit_index($i)->getId();
              }
              ?>
              <select name="einheiten[]" multiple size="<?php echo $einheiten->anzahl_eintraege(); ?>" class="select">
                <?php for ($i=0; $i<$einheiten->anzahl_eintraege(); $i++) { 
                  $einheit = $einheiten->eintrag_mit_index($i); 
                  if ($einheit->getUebergeordnet() == "") {?>
                  <option value="<?php echo $einheit->getId(); ?>" <?php  if (array_search($einheit->getId(), $zugewiesen) !== FALSE) { echo "selected='selected'"; } ?>>
                    <?php echo $einheit->getBezeichnung(); ?>
                  </option>
                <?php }
                } ?>
              </select> 
            </td> 
          </tr>
          <tr>
            <td colspan="4">
              <input type="submit" name="Speichern" value="Speichern"/>
              <input type="reset" value="Zurücksetzen"/>
              <input type="submit" name="Abbrechen" value="Abbrechen" />
            </td>
          </tr>
        </table>
      </form>
      <?php
  }
}
?>