<?php
include_once(dirname(dirname(dirname(__FILE__)))."/include/db.php");
include_once(dirname(dirname(__FILE__))."/Output/Mail.php");
include_once("Benutzer.php");
include_once "Berechtigung.php"; 
include_once("Berechtigung.php");
include_once("Einheit.php");
include_once("Berechtigungen.php");
include_once("Applikationsteil.php");
include_once("Rolle.php");

class BenutzerLog{

/****************************************************************************************************************
 * Benutzeranmeldung                                                                                            *
 ****************************************************************************************************************/
  function BenutzerAnmelden($benutzername, $passwort) {
	$db = connectDB();
	$benutzer = NULL;
    $sql = "SELECT * FROM usr_benutzer WHERE usr_benutzername='$benutzername' AND usr_passwort='".md5($passwort)."' AND usr_aktiv = 1";
    $resultat = $db->query($sql);
    if ($resultat){
      if ($resultat->num_rows > 0){
        $objekt = $resultat->fetch_object();
	
	    $benutzer = new Benutzer();
        $benutzer->setId($objekt->usr_id);
	    $benutzer->setBenutzername($objekt->usr_benutzername);
	    $benutzer->setName($objekt->usr_name);
	    $benutzer->setVorname($objekt->usr_vorname);
	    $benutzer->setPfadiname($objekt->usr_pfadiname);
	    $benutzer->setEmail($objekt->usr_email);
	    $benutzer->setAktiv($objekt->usr_aktiv);
	    $benutzer->setInitial($objekt->usr_initial);
		
        /*$berechtigungen = new Liste();
		$sql = "SELECT * FROM ber_berechtigungen LEFT JOIN grp_gruppe ON ber_gruppe = grp_id WHERE ber_benutzer=".$benutzer->getId();
echo $sql;
        $res = $db->query($sql);
        while ($ber = $res->fetch_object()) {
echo "1";
	      $berechtigung = new Berechtigung();
          $berechtigung->setId($ber->grp_id);
	      $berechtigung->setGruppenname($ber->grp_name);
	      $berechtigung->setBestellen($ber->grp_bestellen);
	      $berechtigungen->eintrag_hinzufuegen($berechtigung);
	      
		  $berechtigungen = $this->BerechtigungenRek($berechtigung->getId(), $berechtigungen);
		}
       
		$benutzer->setBerechtigungen($berechtigungen);*/
	  }
	}
	
    $db->close();
    return $benutzer;
  }

  function Abmelden() {
    $_SESSION = array();
    session_destroy();
  }

/****************************************************************************************************************
 * Auslesefunktionen Benutzer                                                                                   *
 ****************************************************************************************************************/
  
  function BenutzerListe() {
	$db = connectDB();
	$benutzerliste = new Liste();
    $sql = "SELECT * FROM usr_benutzer ORDER BY usr_benutzername";
    $resultat = $db->query($sql);
    while ($objekt = $resultat->fetch_object()) {
	    $benutzer = new Benutzer();
        $benutzer->setId($objekt->usr_id);
	    $benutzer->setBenutzername($objekt->usr_benutzername);
	    $benutzer->setName($objekt->usr_name);
	    $benutzer->setVorname($objekt->usr_vorname);
	    $benutzer->setPfadiname($objekt->usr_pfadiname);
	    $benutzer->setEmail($objekt->usr_email);
	    $benutzer->setAktiv($objekt->usr_aktiv);
	    $benutzer->setInitial($objekt->usr_initial);
		
        /*$berechtigungen = new Liste();
		$sql = "SELECT * FROM ber_berechtigungen LEFT JOIN grp_gruppe ON ber_gruppe = grp_id WHERE ber_benutzer=".$benutzer->getId();
        $res = $db->query($sql);
        while ($ber = $res->fetch_object()) {
	      $berechtigung = new Berechtigung();
          $berechtigung->setId($ber->grp_id);
	      $berechtigung->setGruppenname($ber->grp_name);
	      $berechtigung->setBestellen($ber->grp_bestellen);
	      $berechtigungen->eintrag_hinzufuegen($berechtigung);
	      
		  $berechtigungen = $this->BerechtigungenRek($berechtigung->getId(), $berechtigungen);
		}
	      $berechtigungen = new Liste();
		  $berechtigung = new Berechtigung();
          $berechtigung->setId("1");
	      $berechtigung->setGruppenname("Materialverwalter");
	      $berechtigung->setBestellen("0");
	      $berechtigungen->eintrag_hinzufuegen($berechtigung);
		  
		  
		  
		$benutzer->setBerechtigungen($berechtigungen);
		*/
		
	  $benutzerliste->eintrag_hinzufuegen($benutzer);
	}
	
    $db->close();
    return $benutzerliste;
  }
  
  function Benutzer($benutzerId) {
	$db = connectDB();
	$benutzer = NULL;
    $sql = "SELECT * FROM usr_benutzer WHERE usr_id='$benutzerId'";
    $resultat = $db->query($sql);
    if ($resultat){
      if ($resultat->num_rows > 0){
        $objekt = $resultat->fetch_object();
	
	    $benutzer = new Benutzer();
        $benutzer->setId($objekt->usr_id);
	    $benutzer->setBenutzername($objekt->usr_benutzername);
	    $benutzer->setName($objekt->usr_name);
	    $benutzer->setVorname($objekt->usr_vorname);
	    $benutzer->setPfadiname($objekt->usr_pfadiname);
	    $benutzer->setEmail($objekt->usr_email);
	    $benutzer->setAktiv($objekt->usr_aktiv);
	    $benutzer->setInitial($objekt->usr_initial);
		
        /*$berechtigungen = new Liste();
		$sql = "SELECT * FROM ber_berechtigungen LEFT JOIN grp_gruppe ON ber_gruppe = grp_id WHERE ber_benutzer=".$benutzer->getId();
echo $sql."<br>";
        $res = $db->query($sql);
        while ($ber = $res->fetch_object()) {
	      $berechtigung = new Berechtigung();
          $berechtigung->setId($ber->grp_id);
	      $berechtigung->setGruppenname($ber->grp_name);
	      $berechtigung->setBestellen($ber->grp_bestellen);
	      $berechtigungen->eintrag_hinzufuegen($berechtigung);
	      
		  $berechtigungen = $this->BerechtigungenRek($berechtigung->getId(), $berechtigungen);
		}
		$benutzer->setBerechtigungen($berechtigungen);*/
	  }
	}
	
    $db->close();
    return $benutzer;
  }
  
/****************************************************************************************************************
 * Bearbeitungsfunktionen Benutzer                                                                              *
 ****************************************************************************************************************/
 
   function BenutzerSpeichern($benutzer) {
	$db = connectDB();
	if ($benutzer['name'] != "" && $benutzer['vorname'] != "" && $benutzer['email'] != "") {  
	  if ($benutzer['id'] == "") {  
	    if ($benutzer['pfadiname'] != "") {  
		  $benutzername = strtolower($benutzer['pfadiname']);
		} else {
		  $benutzername = strtolower($benutzer['vorname']);
		}
		$sql = "SELECT * FROM `usr_benutzer` WHERE `usr_benutzername` LIKE '$benutzername%' ORDER BY usr_benutzername";
	    $resultat = $db->query($sql); 
		$usr = "";
		if ($resultat){
          if ($resultat->num_rows > 0){
		    while ($objekt = $resultat->fetch_object()) {
			  $usr = $objekt->usr_benutzername;	
			}
		  }
		}
		
		if ($usr != "") {
		  $prefix = substr($usr, strlen($benutzername));
		  $benutzername .= (++$prefix);
		}
	  
        $sql = "INSERT INTO `usr_benutzer`(`usr_benutzername`, `usr_passwort`, `usr_name`, `usr_vorname`, `usr_pfadiname`, ".
		       "`usr_email`, `usr_aktiv`, `usr_initial`) VALUES ('$benutzername','".md5($this->PasswortGenerator())."','".
			   $benutzer['name']."','".$benutzer['vorname']."','".$benutzer['pfadiname']."','".$benutzer['email']."','".
			   $benutzer['aktiv']."',1)";
	    $db->query($sql); 
        $benId = $db->insert_id;
	  } else {
		if ($benutzer['pfadiname'] != "") {  
		  if(substr($benutzer['benutzername'], 0, strlen($benutzer['pfadiname'])) != strtolower($benutzer['pfadiname'])){
              echo substr($benutzer['benutzername'], 0, strlen($benutzer['pfadiname'])) ." = ". strtolower($benutzer['pfadiname']);
			$benutzername = strtolower($benutzer['pfadiname']);
		    $sql = "SELECT * FROM `usr_benutzer` WHERE `usr_benutzername` LIKE '$benutzername%' ORDER BY usr_benutzername";
	        $resultat = $db->query($sql); 
		    $usr = "";
		    if ($resultat){
              if ($resultat->num_rows > 0){
		        while ($objekt = $resultat->fetch_object()) {
			      $usr = $objekt->usr_benutzername;	
			    }
		      }
		    }
		
		    if ($usr != "") { 
		      $prefix = substr($usr, strlen($benutzername));
		      $benutzername .= (++$prefix);
		    }
			$sql = "UPDATE `usr_benutzer` SET `usr_benutzername`='$benutzername' WHERE `usr_id`=".$benutzer['id'];
			
			if($benutzer['aktiv']) {
             $mail = new Mail();
             $mail->AenderungBenutzername($benutzername, $benutzer['email']);
			}
		  }
		} 
        $sql = "UPDATE `usr_benutzer` SET `usr_name`='".$benutzer['name']."',`usr_vorname`='".$benutzer['vorname'].
		       "',`usr_pfadiname`='".$benutzer['pfadiname']."',`usr_email`='".$benutzer['email']."',`usr_aktiv`='".$benutzer['aktiv'].
			   "' WHERE `usr_id`=".$benutzer['id'];
	    $db->query($sql); 
        $benId = $benutzer['id'];
	  }
      $rollen = $benutzer['rollen'];
      $sql = "DELETE FROM `uro_benutzerrollen` WHERE `uro_benutzer`=$benId";
      $db->query($sql);
      for($i=0; $i < count($rollen); $i++) {
        $sql = "INSERT INTO `uro_benutzerrollen`(`uro_benutzer`, `uro_rolle`) VALUES ($benId,".$rollen[$i].")";
        $db->query($sql);
      }

	} else {
	  $_SESSION['error'] = "Die Eingegebenen Daten sind unvollständig";	
	}
    $db->close();
  }
 
  function BenutzerEinladen($benutzerId) {
	$benutzer = $this->Benutzer($benutzerId);  
	if ($benutzer != NULL) {
	  $mail = new Mail();
	  $mail->EinladungBenutzer($benutzer);  
	  $passwort = $this->PasswortGenerator();
	  $db = connectDB();
      $sql = "UPDATE `usr_benutzer` SET `usr_passwort`='".md5($passwort)."', `usr_aktiv`=1, `usr_initial`=1 WHERE `usr_id`=".$benutzer->getId();
      $db->query($sql); 
      $db->close();
	  $mail->RuecksetzungPasswort($passwort, $benutzer->getEmail());  
	  $_SESSION['error'] = "Dem Benutzer ".ucfirst($benutzer->getBenutzername())." wurde die Einladung versendet.";
	}
  }

/****************************************************************************************************************
 * Passwortfunktionen                                                                                           *
 ****************************************************************************************************************/
 
  function PasswortZuruecksetzen($benutzername) {
	$db = connectDB();
    $sql = "SELECT * FROM usr_benutzer WHERE (usr_benutzername='".strtolower($benutzername)."' OR usr_email='".strtolower($benutzername)."') AND `usr_aktiv`=1";
    $resultat = $db->query($sql);
	$loeschen = TRUE;
    if ($resultat){
      if ($resultat->num_rows > 0){
		$benutzer = $resultat->fetch_object();
 	    $mail = new Mail();
	    $passwort = $this->PasswortGenerator();
        $sql = "UPDATE `usr_benutzer` SET `usr_passwort`='".md5($passwort)."', `usr_initial`=1 WHERE `usr_id`=".$benutzer->usr_id;
        $db->query($sql); 
	    $mail->RuecksetzungPasswort($passwort, $benutzer->usr_email);  
	    $_SESSION['error'] = "Das Passwort wurde zurückgesetzt. Das neue Passwort erhälst du in den nächsten Minuten per eMail.";
	  } else {
	    $_SESSION['error'] = "Der Benutzername wurde nicht erkannt. Bitte wende dich an die Materialverwalter.";
	  }
	}
    $db->close();
  }
 
  function PasswortSpeichern($pwform) {
	$db = connectDB();
    $sql = "SELECT * FROM usr_benutzer WHERE usr_id='".$pwform['id']."' AND `usr_passwort`='".md5($pwform['alt'])."'";
    $resultat = $db->query($sql);
	$loeschen = TRUE;
    if ($resultat){
      if ($resultat->num_rows > 0){
		if ($pwform['neu1'] == $pwform['neu2']){
          $sql = "UPDATE `usr_benutzer` SET `usr_passwort`='".md5($pwform['neu1'])."', `usr_initial`=0 WHERE `usr_id`=".$pwform['id'];
          $db->query($sql); 
          $_SESSION['error'] = "Das Passwort wurde neu gesetzt.";
		} else {
	      $_SESSION['error'] = "Die beiden neuen Passwörter stimmen nicht überein.";
		}
	  } else {
	    $_SESSION['error'] = "Das eingegebene alte Passwort ist ungültig.";
	  }
	}
    $db->close();
	return $this->Benutzer($pwform['id']);
  }
  
/****************************************************************************************************************
 * Hilfsfunktionen                                                                                              *
 ****************************************************************************************************************/
  
  function LoeschenPruefen($benutzerId) {
	$db = connectDB();
    $sql = "SELECT * FROM bst_bestellung WHERE bst_kontakt='$benutzerId'";
    $db->close();
    $resultat = $db->query($sql);
	$loeschen = TRUE;
    if ($resultat){
      if ($resultat->num_rows > 0){
		$loeschen = FALSE;
	  }
	}
    return $loeschen;
  }

  function BenutzerLoeschen($benutzerId) {
	$db = connectDB();
    $sql = "DELETE FROM `usr_benutzer` WHERE `usr_id`='$benutzerId'";
    $db->query($sql);
    $db->close();
  }
  
/****************************************************************************************************************
 * Funktionen zur Bearbeitung der Berechtigungen                                                                *
 ****************************************************************************************************************/
  
  function RollenListe(){  
    $sql = "SELECT rol_id, rol_bezeichnung, rol_beschreibung FROM rol_rollen ORDER BY rol_bezeichnung";
	$db = connectDB();
    $result = $db->query($sql);

    $ergebnis = new Liste();
    while ($object = $result->fetch_object()) {
      $rol = new Rolle();
      $rol->setId($object->rol_id);
      $rol->setBezeichnung($object->rol_bezeichnung);
      $rol->setBeschreibung($object->rol_beschreibung);
        
      // Berechtigungen für Applikationsteile
      $sql = "SELECT ber_applikationsteil, apt_bezeichnung, ber_lesen, ber_schreiben, ber_loeschen FROM ber_berechtigungen LEFT JOIN ".
             "apt_applikationsteile ON ber_applikationsteil = apt_id WHERE ber_rolle = ".$rol->getId()." ORDER BY apt_bezeichnung";
      $tmp = $db->query($sql);
      $berechtigungen = new Berechtigungen();
      while($tmp_object = $tmp->fetch_object()){
        $ber = new Applikationsteil();
        $ber->setId($tmp_object->ber_applikationsteil);
        $ber->setBezeichnung($tmp_object->apt_bezeichnung);
        $ber->setLesen($tmp_object->ber_lesen);
        $ber->setSchreiben($tmp_object->ber_schreiben);
        $ber->setLoeschen($tmp_object->ber_loeschen);
          
        $berechtigungen->applikationsteil_hinzufuegen($ber);
      }
      $rol->setBerechtigungen($berechtigungen);
      
      // Bestellungen für Einheiten
      $sql = "SELECT ein_einheiten.* FROM ein_einheiten LEFT  JOIN `rei_rolleneinheiten` ON rei_einheit = ein_id WHERE rei_rolle = ".$rol->getId();
      $tmp = $db->query($sql);
      $einheiten = new Liste();
      while($tmp_object = $tmp->fetch_object()){
        $ein = new Einheit();
        $ein->setId($tmp_object->ein_id);
        $ein->setBezeichnung($tmp_object->ein_bezeichnung);
        $ein->setUebergeordnet($tmp_object->ein_uebergeordnet);
        $ein->setBestellen($tmp_object->ein_bestellen);
        
        $einheiten->eintrag_hinzufuegen($ein); 
      }

      $rol->setEinheiten($einheiten);
      
      $ergebnis->eintrag_hinzufuegen($rol);
    }
    $db->close();
    return $ergebnis;
  }

  function NeueRolle() {
	$rolle = new Rolle();

	$berechtigungen = new Berechtigungen();
	$sql = "SELECT * FROM apt_applikationsteile ORDER BY apt_bezeichnung";
	$db = connectDB();
    $result = $db->query($sql);
    $db->close();
	  
    while ($object = $result->fetch_object()) {
      $apl = new Applikationsteil();
      $apl->setId($object->apt_id);
      $apl->setBezeichnung($object->apt_bezeichnung);
      
      $berechtigungen->applikationsteil_hinzufuegen($apl);
    }
    $rolle->setBerechtigungen($berechtigungen);

	return $rolle;
  }
  
  function RolleSpeichern($rolle) {
	$db = connectDB();
	$sql = "SELECT * FROM apt_applikationsteile";
    $result = $db->query($sql);
    if ($rolle['id'] == "") {
      $sql = "INSERT INTO `rol_rollen`(`rol_bezeichnung`, `rol_beschreibung`) VALUES ('".$rolle['bezeichnung']."','".$rolle['beschreibung']."')";
      $db->query($sql);
      $rolid = $db->insert_id;
    } else {
      $sql= "UPDATE `rol_rollen` SET `rol_bezeichnung`='".$rolle['bezeichnung']."',`rol_beschreibung`='".$rolle['beschreibung']."' WHERE `rol_id`=".$rolle['id'];
      $db->query($sql);
      $rolid = $rolle['id'];
    }
    
    while ($object = $result->fetch_object()) {
      $lesen = $rolle['lesen_'.$object->apt_id];
      $schreiben = $rolle['schreiben_'.$object->apt_id];
      $loeschen = $rolle['loeschen_'.$object->apt_id];

      if ($rolle['id'] == "") {
        $sql = "INSERT INTO `ber_berechtigungen`(`ber_rolle`, `ber_applikationsteil`, `ber_lesen`, `ber_schreiben`, `ber_loeschen`) VALUES ($rolid,".$object->apt_id.",".
              ($lesen ? $lesen : 0).",".($schreiben ? $schreiben : 0).",".($loeschen ? $loeschen : 0).")";
      } else {
        $sql = "UPDATE `ber_berechtigungen` SET `ber_lesen`=".($lesen ? $lesen : 0).",`ber_schreiben`=".($schreiben ? $schreiben : 0).",`ber_loeschen`=".
              ($loeschen ? $loeschen : 0)." WHERE `ber_rolle`=".$rolle['id']." AND `ber_applikationsteil`=".$object->apt_id;
      }
      $db->query($sql);
    }
   
    $einheit = $rolle['einheiten'];
    $sql = "DELETE FROM `rei_rolleneinheiten` WHERE `rei_rolle`=".$rolid;
    $db->query($sql);
    for($i=0; $i < count($einheit); $i++) {
      $sql = "INSERT INTO `rei_rolleneinheiten`(`rei_rolle`, `rei_einheit`) VALUES ($rolid,".$einheit[$i].")";
      $db->query($sql);
    }
    $db->close();     
  }
  
  function RolleBenutzer($rolle, $benutzer){  
    $sql = "SELECT * FROM `uro_benutzerrollen` WHERE `uro_rolle`='$rolle' AND `uro_benutzer`='$benutzer'";
	$db = connectDB();
    $resultat = $db->query($sql);
    if ($resultat){
      if ($resultat->num_rows > 0){
        $db->close();
        return TRUE;
	  } else {
        $db->close();
        return FALSE;
	  }
	}
  }
  
  function BerechtigungAbfragen($benutzerId, $appId) {
    $berechtigung = new Berechtigung();  
    
    $sql = "SELECT * FROM  `ber_berechtigungen` LEFT JOIN  `uro_benutzerrollen` ON uro_rolle = ber_rolle WHERE uro_benutzer =$benutzerId AND ber_applikationsteil =$appId";
    $db = connectDB();
    $result = $db->query($sql);
    $db->close();
	  
    $object = $result->fetch_object();
    $berechtigung->setApplikationsteil($object->ber_applikationsteil);
    $berechtigung->setLesen($object->ber_lesen);
    $berechtigung->setSchreiben($object->ber_schreiben);
    $berechtigung->setLoeschen($object->ber_loeschen);
    
    return $berechtigung;
  }
  
 /****************************************************************************************************************
 * Bearbeitungsfunktionen Einheiten                                                                             *
 ****************************************************************************************************************/
   function EinheitenListe() {
	$db = connectDB();
	$einheiten = new Liste();
    $sql = "SELECT * FROM  `ein_einheiten` WHERE  `ein_uebergeordnet` IS NULL";
    $resultat = $db->query($sql);
    while ($objekt = $resultat->fetch_object()) {
      $einheit = new Einheit();
      $einheit->setId($objekt->ein_id);
      $einheit->setBezeichnung($objekt->ein_bezeichnung);
      $einheit->setUebergeordnet($objekt->ein_uebergeordnet);
      $einheit->setBestellen($objekt->ein_bestellen);
      $einheiten->eintrag_hinzufuegen($einheit);
      
      $einheiten = $this->EinheitenRek($objekt->ein_id, $einheiten);
	}
	
    $db->close();
    return $einheiten;  
  }
 
    function EinheitenBenutzer($usrId) {
	$db = connectDB();
	$einheiten = new Liste();
    $sql = "SELECT `ein_einheiten`.* FROM `uro_benutzerrollen` LEFT JOIN `rei_rolleneinheiten` ON `rei_rolle` = `uro_rolle` ".
	       "LEFT JOIN `ein_einheiten` ON `rei_einheit` = `ein_id` WHERE `uro_benutzer`= $usrId";
    $resultat = $db->query($sql);
    while ($objekt = $resultat->fetch_object()) {
      $einheit = new Einheit();
      $einheit->setId($objekt->ein_id);
      $einheit->setBezeichnung($objekt->ein_bezeichnung);
      $einheit->setUebergeordnet($objekt->ein_uebergeordnet);
      $einheit->setBestellen($objekt->ein_bestellen);
      $einheiten->eintrag_hinzufuegen($einheit);
      
      $einheiten = $this->EinheitenRek($objekt->ein_id, $einheiten);
	}
	
    $db->close();
    return $einheiten;  
  }
  
/****************************************************************************************************************
 * Private Funktionen der Klasse                                                                                *
 ****************************************************************************************************************/
  
  private function BerechtigungenRek($gruppe, $berechtigungen) {
	$db = connectDB();
    $sql = "SELECT * FROM grp_gruppe WHERE grp_uebergeordnet=$gruppe";
	$res = $db->query($sql);
    while ($ber = $res->fetch_object()) {

	  $berechtigung = new Berechtigung();
      $berechtigung->setId($ber->grp_id);
	  $berechtigung->setGruppenname($ber->grp_name);
	  $berechtigung->setBestellen($ber->grp_bestellen);
	  $berechtigungen->eintrag_hinzufuegen($berechtigung);
	  $berechtigungen = $this->BerechtigungenRek($berechtigung->getId(), $berechtigungen);
	}
	return $berechtigungen;
    $db->close();
  }

  private function EinheitenRek($einheit, $einheiten) {
	$db = connectDB();
    $sql = "SELECT * FROM ein_einheiten WHERE ein_uebergeordnet=$einheit";
	$res = $db->query($sql);
    while ($objekt = $res->fetch_object()) {
      $einheit = new Einheit();
      $einheit->setId($objekt->ein_id);
      $einheit->setBezeichnung($objekt->ein_bezeichnung);
      $einheit->setUebergeordnet($objekt->ein_uebergeordnet);
      $einheit->setBestellen($objekt->ein_bestellen);
      $einheiten->eintrag_hinzufuegen($einheit);
    }
    $db->close();
    return $einheiten; 
  }

  private function PasswortGenerator(){
	$src = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890$£!?#%&/";
    $pwd = "";
	for ($i = 0; $i < 8; $i++){
	  $index = rand(0, strlen($src)-1);
	  $pwd .= substr($src, $index, 1);
	}
    return $pwd;	  
  }
}
?>