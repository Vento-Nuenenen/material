<?php

/*include_once(dirname(dirname(dirname(__FILE__)))."/include/db.php");
include_once(dirname(dirname(__FILE__))."/Liste.php");
include_once(dirname(dirname(__FILE__))."/Material/Material.php");
include_once(dirname(dirname(__FILE__))."/Material/Materialgruppe.php");
include_once(dirname(dirname(__FILE__))."/Output/Mail.php");
include_once("Bestellung.php");*/

class BestellungenDatenLog{

/*************************************************************************************************************************************
 * Funktionen                                                                                                                        *
 *************************************************************************************************************************************/

 
  function Formulardaten($id, $von, $bis) {
	if ($id == NULL || $id == ""){
	  $id = "NULL";	
	}

	$db = connectDB();
    $sql = "SELECT * FROM `mgr_materialgruppen` WHERE mgr_bestellposition IS NOT NULL ORDER BY mgr_bestellposition";
    $resultat = $db->query($sql);
    $formulardaten = new Liste();

    while ($object = $resultat->fetch_object()) {
      $gruppe = new Materialgruppe();
	  $gruppe->setId($object->mgr_id);
	  $gruppe->setBezeichnung($object->mgr_bezeichnung);

      $materialliste = new Liste();
	  $sql = "SELECT * FROM `mat_material` LEFT OUTER JOIN `mab_materialbestellung` ON mat_id = mab_material AND `mab_bestellung`=$id WHERE `mat_gruppe`=".$object->mgr_id."  ORDER BY `mat_bezeichnung`";
	  $res = $db->query($sql);
      while ($mat = $res->fetch_object()) {
        $material = new Material();
        $material->setId($mat->mat_id);				
        $material->setBezeichnung($mat->mat_bezeichnung);   
        $material->setBestand($mat->mat_bestand);            
        $material->setAlltagsbestand($mat->mat_alltagsbestand);   
        $material->setVerbrauchsmaterial($mat->mat_verbrauchsmaterial);

		$sql = "SELECT COALESCE(sum(mab_menge), 0) ausgeliehen FROM `mab_materialbestellung` LEFT JOIN bst_bestellung ON mab_bestellung = bst_id WHERE mab_material='".$mat->mat_id.
		       "' AND (bst_von<='$bis' AND bst_von>='$von' OR bst_bis<='$bis' AND bst_bis>='$von')";
	    if ($id != "NULL") {
          $sql .=" AND mab_bestellung <> $id";	
		}
          
	    $ausg = $db->query($sql);
		$aus = $ausg->fetch_object();
        $material->setAusgeliehen($aus->ausgeliehen); 
        $material->setMaterialgruppe($mat->mat_gruppe);  
        $material->setPackgroesse($mat->mat_packgroesse);  
        $material->setBestellmenge($mat->mab_menge);  

	    $materialliste->eintrag_hinzufuegen($material);
	  }

	  $gruppe->setAnzahl($materialliste->anzahl_eintraege());
	  $gruppe->setArtikel($materialliste);

	  $formulardaten->eintrag_hinzufuegen($gruppe);
	}

    $db->close();
	return $formulardaten;
  }

 function Einheit($id) {
	$db = connectDB();
    $sql = "SELECT * FROM `ein_einheiten` WHERE ein_id = $id";
    $resultat = $db->query($sql);
    $object = $resultat->fetch_object();
    $db->close();	  
	return $object->ein_bezeichnung;
  }   
    
  function Kontakte($einheit) {
    $db = connectDB();
    $select = "SELECT `usr_benutzer`.* FROM `rei_rolleneinheiten`LEFT JOIN `uro_benutzerrollen` ON `rei_rolle` = `uro_rolle` LEFT JOIN ".
	          "`usr_benutzer` ON `uro_benutzer` = `usr_id` WHERE `rei_einheit` = $einheit AND `rei_rolle` != 1 AND `usr_aktiv` = 1";

    $resultat = $db->query($select);
    $liste = new Liste();

    while ($objekt = $resultat->fetch_object()) {
      $benutzer = new Benutzer();
      $benutzer->setId($objekt->usr_id);
	  $benutzer->setBenutzername($objekt->usr_benutzername);

        // Todo rest

      $liste->eintrag_hinzufuegen($benutzer);
	}

    $db->close();	  
	return $liste;
  }    
    
   /*function GrunddatenPruefen($daten) {
    $dateTimestamp1 = strtotime($daten["von"]);
    $dateTimestamp2 = strtotime($daten["bis"]);
    $heute = strtotime(date("d.m.Y"));

	$bestellung = $_SESSION['bestellung'];
	$bestellung->setEinheit($daten["einheit"]); 
	//$bestellung->setKontakt($daten["kontakt"]); 
	$bestellung->setVon($dateTimestamp1); 
	$bestellung->setBis($dateTimestamp2); 
	$bestellung->setAnlass($daten["anlass"]); 
	$_SESSION['bestellung'] = $bestellung;

	if ($daten['einheit'] == "") {
      $_SESSION["error"] = "keine Einheit ausgewählt";
      $_GET['step'] = 1;	
	} else if ($daten['anlass'] == "") {	  
      $_SESSION["error"] = "kein Anlass angegeben";
      $_GET['step'] = 1;	
	} else {
      if ($dateTimestamp1 >= $heute) {
        if ($dateTimestamp1 > $dateTimestamp2) {
          $_SESSION["error"] = "Das Rückgabedatum liegt vor dem Verfügbar-Datum";
          $_GET['step'] = 1;	
        }
      } else {
        $_SESSION["error"] = "Das Verfügbar-Datum liegt in der Vergangenheit";
        $_GET['step'] = 1;	
      }
	}
  }

  function BestellungenListe($benutzer) {
 	$db = connectDB();
    // Berechtigte Einheiten ermitteln 
    $where = "";
    $sql = "SELECT `rei_einheit` FROM `uro_benutzerrollen` LEFT JOIN `rei_rolleneinheiten` ON `uro_rolle` = `rei_rolle` WHERE `uro_benutzer` = $benutzer";  
    $result = $db->query($sql);
    $i = 1;
    
    while ($object = $result->fetch_object()) {
      if ($i == 1) {
        $where = " WHERE `bst_einheit`=".$object->rei_einheit; 
      } else {
        $where .= " OR `bst_einheit`=".$object->rei_einheit; 
      }
      $i++;
	  $where = $this->BerechtigungsRek($object->rei_einheit, $where);
    }

    // Bestellungsliste erstellen
	$select = "SELECT * FROM `bst_bestellung` LEFT JOIN `ein_einheiten` ON `bst_einheit` = `ein_id` LEFT JOIN usr_benutzer ON bst_kontakt = usr_id".$where;
    if ($status == 4) {
      $select .= " AND bst_bis > ".(strtotime(date("d.m.Y"))-365*24*60*60);   
    }

	$select .= " ORDER BY `bst_status`, `bst_von`";
	$bestellungen = new Liste();
    $result = $db->query($select);

    while ($object = $result->fetch_object()) {
	  $bestellung = new Bestellung();
      $bestellung->setId($object->bst_id);
      $bestellung->setEinheit($object->bst_einheit);
      $bestellung->setEinheitBez($object->ein_bezeichnung);
      $bestellung->setKontakt($object->bst_kontakt);
      $bestellung->setKontaktBez($object->usr_benutzername);
      $bestellung->setAnlass($object->bst_anlass);
      $bestellung->setVon($object->bst_von);
      $bestellung->setBis($object->bst_bis);
      $bestellung->setStatus($object->bst_status);

	  $matbestellung = new Liste();
      $sql = "SELECT * FROM `mab_materialbestellung` LEFT JOIN `mat_material` ON `mab_material` = `mat_id` WHERE `mab_bestellung`=".$object->bst_id." ORDER BY `mat_gruppe`, `mat_bezeichnung`";
      $resultat = $db->query($sql);

      while ($objekt = $resultat->fetch_object()) {
	    $material = new Material();	
        $material->setId($objekt->mat_id);				
        $material->setBezeichnung($objekt->mat_bezeichnung);   
        $material->setBestand($objekt->mat_bestand);
        $material->setAlltagsbestand($objekt->mat_alltagsbestand);
        $material->setVerbrauchsmaterial($objekt->mat_verbrauchsmaterial);
        $material->setMaterialgruppe($objekt->mat_gruppe);
        $material->setPackgroesse($objekt->mat_packgroesse);
        $material->setBestellmenge($objekt->mab_menge);
        $material->setBestaetigtemenge($objekt->mab_bestaetigt);  
        $material->setAusgabemenge($objekt->mab_ausgabe);
        $material->setRueckgabemenge($objekt->mab_rueckgabe);
          
          $material->setRueckgabeReparatur($objekt->mab_reparatur);  
        $material->setRueckgabeDefekt($objekt->mab_defekt);  
          
	    $matbestellung->eintrag_hinzufuegen($material);
	  }

      $bestellung->setMatbestellung($matbestellung);
      $bestellungen->eintrag_hinzufuegen($bestellung);
	}

    $db->close();
	return $bestellungen;
  }

  function BestellungenStatus($status) {

 	$db = connectDB();

    // Bestellungsliste erstellen
	$select = "SELECT * FROM `bst_bestellung` LEFT JOIN `ein_einheiten` ON `bst_einheit` = `ein_id` LEFT JOIN usr_benutzer ON bst_kontakt = usr_id WHERE bst_status = ".$status;
    if ($status == 4) {
      $select .= " AND bst_bis > ".(strtotime(date("d.m.Y"))-365*24*60*60);   
    }
	$select .= " ORDER BY `bst_bis` DESC";

	$bestellungen = new Liste();
    $result = $db->query($select);
      
    while ($object = $result->fetch_object()) {
	  $bestellung = new Bestellung();
      $bestellung->setId($object->bst_id);
      $bestellung->setEinheit($object->bst_einheit);
      $bestellung->setEinheitBez($object->ein_bezeichnung);
      $bestellung->setKontakt($object->bst_kontakt);
      $bestellung->setKontaktBez($object->usr_benutzername);
      $bestellung->setAnlass($object->bst_anlass);
      $bestellung->setVon($object->bst_von);
      $bestellung->setBis($object->bst_bis);
      $bestellung->setStatus($object->bst_status);

	  $matbestellung = new Liste();
      $sql = "SELECT * FROM `mab_materialbestellung` LEFT JOIN `mat_material` ON `mab_material` = `mat_id` WHERE `mab_bestellung`=".$object->bst_id." ORDER BY `mat_gruppe`, `mat_bezeichnung`";
      $resultat = $db->query($sql);

      while ($objekt = $resultat->fetch_object()) {
	    $material = new Material();	
        $material->setId($objekt->mat_id);				
        $material->setBezeichnung($objekt->mat_bezeichnung);   
        $material->setBestand($objekt->mat_bestand);
        $material->setAlltagsbestand($objekt->mat_alltagsbestand);
        $material->setVerbrauchsmaterial($objekt->mat_verbrauchsmaterial);
        $material->setMaterialgruppe($objekt->mat_gruppe);
        $material->setPackgroesse($objekt->mat_packgroesse);
        $material->setBestellmenge($objekt->mab_menge);
        $material->setBestaetigtemenge($objekt->mab_bestaetigt);   
        $material->setAusgabemenge($objekt->mab_ausgabe);
        $material->setRueckgabemenge($objekt->mab_rueckgabe);
        $material->setRueckgabeReparatur($objekt->mab_reparatur);  
        $material->setRueckgabeDefekt($objekt->mab_defekt);  
          
	    $matbestellung->eintrag_hinzufuegen($material);
	  }

      $bestellung->setMatbestellung($matbestellung);
      $bestellungen->eintrag_hinzufuegen($bestellung);
	}

    $db->close();
	return $bestellungen;
  }

  function Bestellung($id) {
	$db = connectDB();
    $sql = "SELECT * FROM `bst_bestellung` LEFT JOIN `ein_einheiten` ON `bst_einheit` = `ein_id` LEFT JOIN usr_benutzer ON bst_kontakt = usr_id WHERE `bst_id`=$id";
	$resultat = $db->query($sql);
    $object = $resultat->fetch_object();

	$bestellung = new Bestellung();
    $bestellung->setId($object->bst_id);
    $bestellung->setEinheit($object->bst_einheit);
    $bestellung->setEinheitBez($object->ein_bezeichnung);
    $bestellung->setKontakt($object->bst_kontakt);
    $bestellung->setKontaktBez($object->usr_benutzername);
    $bestellung->setAnlass($object->bst_anlass);
    $bestellung->setVon($object->bst_von);
    $bestellung->setBis($object->bst_bis);
    $bestellung->setStatus($object->bst_status);

	$matbestellung = new Liste();
    $sql = "SELECT * FROM `mab_materialbestellung` LEFT JOIN `mat_material` ON `mab_material` = `mat_id` WHERE `mab_bestellung`=$id ORDER BY `mat_gruppe`, `mat_bezeichnung`";
    $resultat = $db->query($sql);

    while ($object = $resultat->fetch_object()) {
	  $material = new Material();	
      $material->setId($object->mat_id);				
      $material->setBezeichnung($object->mat_bezeichnung);   
      $material->setBestand($object->mat_bestand);
      $material->setAlltagsbestand($object->mat_alltagsbestand);
      $material->setVerbrauchsmaterial($object->mat_verbrauchsmaterial);
      $material->setMaterialgruppe($object->mat_gruppe);
      $material->setPackgroesse($object->mat_packgroesse);
      $material->setBestellmenge($object->mab_menge);
      $material->setBestaetigtemenge($object->mab_bestaetigt);  
      $material->setAusgabemenge($object->mab_ausgabe);
      $material->setRueckgabemenge($object->mab_rueckgabe);
      $material->setRueckgabeReparatur($object->mab_reparatur);
      $material->setRueckgabeDefekt($object->mab_defekt);
	  $matbestellung->eintrag_hinzufuegen($material);
	}

    $bestellung->setMatbestellung($matbestellung);
    $db->close();
	return $bestellung;
  }

    
  function BestellungSpeichern($daten/*, $benutzer* /) {
echo"Debug 2.1<br>";
	$db = connectDB();
	if ($daten['id'] =="") {
	  $sql = "INSERT INTO `bst_bestellung`(`bst_einheit`, bst_kontakt, `bst_von`, `bst_bis`, `bst_anlass`) VALUES ('".$daten['einheit']."','".$daten['kontakt']."','".$daten['von']."','".$daten['bis'].
	         "','".$daten['anlass']."')";
      $db->query($sql);
	  $id = $db->insert_id;
	} else {
	  $sql = "UPDATE `bst_bestellung` SET `bst_einheit`='".$daten['einheit']."',`bst_kontakt`='".$daten['kontakt']."',`bst_von`='".$daten['von']."',`bst_bis`='".$daten['bis'].
	         "',`bst_anlass`='".$daten['anlass']."' WHERE `bst_id`=".$daten['id'];
      $db->query($sql);
	  $sql = "DELETE FROM `mab_materialbestellung` WHERE mab_bestellung=".$daten['id'];
      $db->query($sql);
	  $id = $daten['id'];
	}
echo"Debug 2.2<br>";
	$matid = $daten['matid'];
	$menge = $daten['menge'];
	for($i=0; $i < count($matid); $i++) {
	  if ($menge[$i] > 0){
	    $sql = "INSERT INTO `mab_materialbestellung`(`mab_bestellung`, `mab_material`, `mab_menge`) VALUES ($id, $matid[$i], $menge[$i])";
        $db->query($sql);
	  }
	}
echo"Debug 2.3<br>";
    $db->close();
    return $this->Bestellung($id);
  }

  function BestellungLoeschen($id) {
    $db = connectDB();
    $sql = "DELETE FROM `mab_materialbestellung` WHERE mab_bestellung=".$id;
    $db->query($sql);
    $sql = "DELETE FROM `bst_bestellung` WHERE bst_id=".$id;
    $db->query($sql);
    $db->close();
  }
    
    
    
  /**************************************************************************************************************
   * Verwaltung der Bestellungen
   ************************************************************************************************************** /
  function BestellungWeiterleiten($id) {
	$db = connectDB();
    $sql = "UPDATE `bst_bestellung` SET `bst_status`=1 WHERE `bst_id`=".$id;
    $db->query($sql);
	$bst = $this->Bestellung($id);
	$mail = new Mail();
	$mail->WeiterleitungBestellung($bst);
    $db->close();
  }

  function BestellungBestaetigen($daten) {
	$db = connectDB();
    
    $bestellung = $daten['bestellung'];
    $sql = "UPDATE `bst_bestellung` SET `bst_status`=2 WHERE `bst_id`=".$bestellung;
    $db->query($sql);
      
	$matid = $daten['matid'];
	$menge = $daten['menge'];
	for($i=0; $i < count($matid); $i++) {
	  $sql = "UPDATE `mab_materialbestellung` SET `mab_bestaetigt`=$menge[$i] WHERE `mab_bestellung`=$bestellung AND `mab_material`=$matid[$i]";
      $db->query($sql);
	}

    $db->close();
    return $this->Bestellung($bestellung);
  }   
    
  function BestellungErgaenzen($daten) {
	$db = connectDB();
    
    $id = $daten['bestellung'];
      
	$matid = $daten['matid'];
	$menge = $daten['menge'];
	for($i=0; $i < count($matid); $i++) {
	  $sql = "UPDATE `mab_materialbestellung` SET `mab_ausgabe`=$menge[$i] WHERE `mab_bestellung`=$id AND `mab_material`=$matid[$i]";
      $db->query($sql);
	}

      
    $materialliste = new Liste();  
    $sql = "SELECT * FROM `mat_material` WHERE `mat_id` NOT IN (SELECT `mab_material` FROM `mab_materialbestellung` WHERE  `mab_bestellung` = $id) AND `mat_bestand` > 0 ORDER BY `mat_gruppe`, `mat_bezeichnung`";
      
    $res = $db->query($sql);
    while ($mat = $res->fetch_object()) {
      $material = new Material();
      $material->setId($mat->mat_id);				
      $material->setBezeichnung($mat->mat_bezeichnung);   
      $material->setBestand($mat->mat_bestand);            
      $material->setAlltagsbestand($mat->mat_alltagsbestand);   

		/*$sql = "SELECT COALESCE(sum(mab_menge), 0) ausgeliehen FROM `mab_materialbestellung` LEFT JOIN bst_bestellung ON mab_bestellung = bst_id WHERE mab_material='".$mat->mat_id.
		       "' AND (bst_von<='$bis' AND bst_von>='$von' OR bst_bis<='$bis' AND bst_bis>='$von')";
	    if ($id != "NULL") {
          $sql .=" AND mab_bestellung <> $id";	
		}
          
	    $ausg = $db->query($sql);
		$aus = $ausg->fetch_object();
        $material->setAusgeliehen($aus->ausgeliehen); * /
        $material->setAusgeliehen(0);
        
        
      $material->setMaterialgruppe($mat->mat_gruppe);  
      $material->setPackgroesse($mat->mat_packgroesse);  
      //$material->setBestellmenge($mat->mab_menge);  

      $materialliste->eintrag_hinzufuegen($material);
    }
      
    $db->close();
      
    return $materialliste;
  } 
    
  function BestellungBereitgestellt($daten) {
	$db = connectDB();
    
    $id = $daten['bestellung'];
    $sql = "UPDATE `bst_bestellung` SET `bst_status`=3 WHERE `bst_id`=".$id;
    $db->query($sql);
      
	$matid = $daten['matid'];
	$menge = $daten['menge'];
	for($i=0; $i < count($matid); $i++) {
	  $sql = "UPDATE `mab_materialbestellung` SET `mab_ausgabe`=$menge[$i], `mab_rueckgabe`=$menge[$i] WHERE `mab_bestellung`=$id AND `mab_material`=$matid[$i]";
      $db->query($sql);
	}
      
    if (isset($daten['n_matid'])) {
      $matid = $daten['n_matid'];
	  $menge = $daten['n_menge'];
	  for($i=0; $i < count($matid); $i++) {
	    if ($menge[$i] > 0){
	      $sql = "INSERT INTO `mab_materialbestellung`(`mab_bestellung` , `mab_material` , `mab_ausgabe`, `mab_rueckgabe`) VALUES ($id, $matid[$i], $menge[$i], $menge[$i])";
          $db->query($sql);
	    }
	  }
    }

    $db->close();
    //return $this->Bestellung($bestellung);
  }    
    
    
  function BestellungRueckgabeZwischenspeichern($daten) {
	$db = connectDB();
    
    $id = $daten['bestellung'];
    /*$sql = "UPDATE `bst_bestellung` SET `bst_status`=4 WHERE `bst_id`=".$id;
    $db->query($sql);* /
      
	$matid = $daten['matid'];
	$menge = $daten['menge'];
    $rep   = $daten['rep'];
    $def   = $daten['def'];
	for($i=0; $i < count($matid); $i++) {
	  $sql = "UPDATE `mab_materialbestellung` SET `mab_rueckgabe`=$menge[$i] WHERE `mab_bestellung`=$id AND `mab_material`=$matid[$i]";
      $db->query($sql);
	
      if ($rep[$i] > 0) {
        $sql = "UPDATE `mab_materialbestellung` SET `mab_reparatur`=$rep[$i] WHERE `mab_bestellung`=$id AND `mab_material`=$matid[$i]";
        $db->query($sql);
          
      }
      if ($def[$i] > 0) {
        $sql = "UPDATE `mab_materialbestellung` SET `mab_defekt`=$def[$i] WHERE `mab_bestellung`=$id AND `mab_material`=$matid[$i]";
        $db->query($sql);
      }
    }

    $db->close();
    //return $this->Bestellung($bestellung);
  }       
    
    
    
  function BestellungRueckgabe($daten) {
	$db = connectDB();
    
    $id = $daten['bestellung'];
    $sql = "UPDATE `bst_bestellung` SET `bst_status`=4 WHERE `bst_id`=".$id;
    $db->query($sql);
      
	$matid = $daten['matid'];
	$menge = $daten['menge'];
    $rep   = $daten['rep'];
	for($i=0; $i < count($matid); $i++) {
	  $sql = "UPDATE `mab_materialbestellung` SET `mab_rueckgabe`=$menge[$i] WHERE `mab_bestellung`=$id AND `mab_material`=$matid[$i]";
      $db->query($sql);
	
      if ($rep[$i] > 0) {
        $sql = "UPDATE `mat_material` SET `mat_reparatur`=`mat_reparatur`+$rep[$i] WHERE `mat_id`=$matid[$i]";
        $db->query($sql);
          
      }
    }

    $db->close();
    //return $this->Bestellung($bestellung);
  }       
    
  function BestellungStatusZuruecksetzen($bestellung)  {
    $db = connectDB();
    $sql = "SELECT `bst_status` FROM `bst_bestellung` WHERE `bst_id`=$bestellung";
	$resultat = $db->query($sql);
    $object = $resultat->fetch_object();
    if ($object->bst_status > 0) {
      $sql = "UPDATE `bst_bestellung` SET `bst_status`=`bst_status`-1 WHERE `bst_id`=$bestellung";
      $db->query($sql); 
    }
      
      
      
      
      
      
  }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
  // ersetzen
  function BestellStatus($id, $status) {
	$db = connectDB();
    $sql = "UPDATE `bst_bestellung` SET `bst_status`=$status WHERE `bst_id`=".$id;
    
     
    $db->query($sql);
	$bst = $this->Bestellung($id);
	$mail = new Mail();
    if ($status == 1) {
	  //$mail->WeiterleitungBestellung($bst); //todo: Wieder einschalten
    } else if ($status == 3) {
        
    }

    $db->close();
  }


  


  function Kontakte($einheit) {
    $db = connectDB();
    $select = "SELECT `usr_benutzer`.* FROM `rei_rolleneinheiten`LEFT JOIN `uro_benutzerrollen` ON `rei_rolle` = `uro_rolle` LEFT JOIN ".
	          "`usr_benutzer` ON `uro_benutzer` = `usr_id` WHERE `rei_einheit` = $einheit AND `rei_rolle` != 1 AND `usr_aktiv` = 1";

    $resultat = $db->query($select);
    $liste = new Liste();

    while ($objekt = $resultat->fetch_object()) {
      $benutzer = new Benutzer();
      $benutzer->setId($objekt->usr_id);
	  $benutzer->setBenutzername($objekt->usr_benutzername);

        // Todo rest

      $liste->eintrag_hinzufuegen($benutzer);
	}

    $db->close();	  
	return $liste;
  }


  function AlleKontakte() {
    $db = connectDB();
    $select = "SELECT DISTINCT `usr_benutzer`.* FROM `rei_rolleneinheiten` LEFT JOIN `uro_benutzerrollen` ON `rei_rolle` = `uro_rolle` ".
              "LEFT JOIN `usr_benutzer` ON `uro_benutzer` = `usr_id` LEFT JOIN rol_rollen ON rei_rolle = rol_id";

    $resultat = $db->query($select);
    $liste = new Liste();
    while ($objekt = $resultat->fetch_object()) {
      $benutzer = new Benutzer();
      $benutzer->setId($objekt->usr_id);
	  $benutzer->setBenutzername($objekt->usr_benutzername);
      
      // Todo rest

      $liste->eintrag_hinzufuegen($benutzer);
	}

    $db->close();	  
	return $liste;
  }


  function StatusUebersicht() {
    $db = connectDB();
    $definierteStati = 5;
    $sql = "SELECT bst_status, COUNT(`bst_id`) bcount FROM `bst_bestellung` WHERE `bst_bis` > ".(strtotime(date("d.m.Y"))-365*24*60*60)." GROUP BY `bst_status` ASC";
    $resultat = $db->query($sql);
    $status = array();
    $i=0;
    
    while ($objekt = $resultat->fetch_object()) {
      while ($objekt->bst_status > $i) {
        $status["$i"] = 0;
        $i++;
      }
      $status["$i"] = $objekt->bcount;
      $i++;
	}

    while ($i <= $definierteStati) {
      $status["$i"] = 0;
      $i++;          
    }

    $db->close();	  
	return $status;
  }


  function GruppenAufwaertsRek($gruppe, $select){
	$db = connectDB();
	$sql = "SELECT * FROM grp_gruppe WHERE grp_id=$gruppe";
	$res = $db->query($sql);
	$objekt = $res->fetch_object();

    if ($objekt->grp_uebergeordnet != NULL && $objekt->grp_uebergeordnet != 1) {
	  $sql = "SELECT * FROM grp_gruppe WHERE grp_id=".$objekt->grp_uebergeordnet;
	  $res = $db->query($sql);
      while ($ber = $res->fetch_object()) {
	    $select .= " OR `ber_gruppe` = ".$ber->grp_id;
	    $select = $this->GruppenAufwaertsRek($ber->grp_id, $select);
	  }
	}

    $db->close();
	return $select;
  }


  private function BerechtigungsRek($gruppe, $select) {
	$db = connectDB();
	$res = $db->query("SELECT * FROM grp_gruppe WHERE grp_uebergeordnet=$gruppe");

    while ($ber = $res->fetch_object()) {
      $select .= " OR `bst_einheit`=".$ber->grp_id;
	  $select = $this->BerechtigungsRek($ber->grp_id, $select);
	}

    $db->close();
	return $select;
  } */

}
?>