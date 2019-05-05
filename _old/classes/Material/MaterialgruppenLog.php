<?php
include_once(dirname(dirname(dirname(__FILE__)))."/include/db.php");
include_once(dirname(dirname(__FILE__))."/Liste.php");
include_once("Materialgruppe.php");

class MaterialgruppenLog{

/*************************************************************************************************************************************
 * Funktionen                                                                                                       *
 *************************************************************************************************************************************/

  function MaterialgruppenListe() {
	$db = connectDB();
    $resultat = $db->query("SELECT * FROM `mgr_materialgruppen` ORDER BY mgr_bezeichnung");

    $gruppen = new Liste();
    
    while ($object = $resultat->fetch_object()) {
      $gruppe = new Materialgruppe();
	  $gruppe->setId($object->mgr_id);
	  $gruppe->setBezeichnung($object->mgr_bezeichnung);

      $res = $db->query("SELECT COUNT(*) anzahl FROM `mat_material` WHERE `mat_gruppe`=".$object->mgr_id);
	  $anzahl = $res->fetch_object();
	  $gruppe->setAnzahl($anzahl->anzahl);

	  $gruppen->eintrag_hinzufuegen($gruppe);
	}
	
    $db->close();
	return $gruppen;
  }

  function MaterialgruppeSpeichern($gruppe) {
	$db = connectDB();
	if ($gruppe['bezeichnung'] != "") {  
	  if ($gruppe['id'] == "") {  
	    $db->query("INSERT INTO `mgr_materialgruppen`(`mgr_bezeichnung`) VALUES ('".$gruppe['bezeichnung']."')"); 
	  } else {
		$db->query("UPDATE `mgr_materialgruppen` SET `mgr_bezeichnung`='".$gruppe['bezeichnung']."' WHERE `mgr_id`=".$gruppe['id']);
	  }
	}
    $db->close();
  }

  function MaterialgruppeLoeschen($gruppe) {
	$db = connectDB();
    $db->query("DELETE FROM `mgr_materialgruppen` WHERE `mgr_id`=".$gruppe['id']);
    $db->close();
  }


  function MaterialgruppenBestellformular() {
	$db = connectDB();
    $resultat = $db->query("SELECT * FROM `mgr_materialgruppen` WHERE mgr_bestellposition IS NOT NULL ORDER BY mgr_bestellposition");

    $gruppen = new Liste();
    
    while ($object = $resultat->fetch_object()) {
      $gruppe = new Materialgruppe();
	  $gruppe->setId($object->mgr_id);
	  $gruppe->setBezeichnung($object->mgr_bezeichnung);

      $res = $db->query("SELECT COUNT(*) anzahl FROM `mat_material` WHERE `mat_gruppe`=".$object->mgr_id);
	  $anzahl = $res->fetch_object();
	  $gruppe->setAnzahl($anzahl->anzahl);

	  $gruppen->eintrag_hinzufuegen($gruppe);
	}
	return $gruppen;
  }

  function MaterialgruppenNichtInBestellformular() {
	$db = connectDB();
    $resultat = $db->query("SELECT * FROM `mgr_materialgruppen` WHERE mgr_bestellposition IS NULL ORDER BY mgr_bezeichnung");

    $gruppen = new Liste();
    
    while ($object = $resultat->fetch_object()) {
      $gruppe = new Materialgruppe();
	  $gruppe->setId($object->mgr_id);
	  $gruppe->setBezeichnung($object->mgr_bezeichnung);

      $res = $db->query("SELECT COUNT(*) anzahl FROM `mat_material` WHERE `mat_gruppe`=".$object->mgr_id);
	  $anzahl = $res->fetch_object();
	  $gruppe->setAnzahl($anzahl->anzahl);

	  $gruppen->eintrag_hinzufuegen($gruppe);
	}
	return $gruppen;
  }

  function GruppeHinzufuegen($gruppe) {
	$db = connectDB();
	
	// Nachfolgende Einträge nach hinten schieben
    $resultat = $db->query("SELECT * FROM `mgr_materialgruppen` WHERE mgr_bestellposition >= ".$gruppe['pos']." ORDER BY mgr_bestellposition DESC");
    while ($object = $resultat->fetch_object()) {
      $db->query("UPDATE `mgr_materialgruppen` SET `mgr_bestellposition`=".($object->mgr_bestellposition+1)." WHERE `mgr_id` = ".$object->mgr_id);
	}

	// Hinzugefügter Eintrag positionieren
    $db->query("UPDATE `mgr_materialgruppen` SET `mgr_bestellposition`=".$gruppe['pos']." WHERE `mgr_id` = ".$gruppe['gruppe']);
	
	$db->close();
  }

  function GruppeAuf($position) {
	$db = connectDB();
	
    $resultat = $db->query("SELECT * FROM `mgr_materialgruppen` WHERE mgr_bestellposition = ".$position);
    $object = $resultat->fetch_object();
    $db->query("UPDATE `mgr_materialgruppen` SET `mgr_bestellposition`=".$position." WHERE `mgr_bestellposition` = ".($position-1));
    $db->query("UPDATE `mgr_materialgruppen` SET `mgr_bestellposition`=".($position-1)." WHERE `mgr_id` = ".$object->mgr_id);

	$db->close();
  }

  function GruppeAb($position) {
	$db = connectDB();
	
    $resultat = $db->query("SELECT * FROM `mgr_materialgruppen` WHERE mgr_bestellposition = ".$position);
    $object = $resultat->fetch_object();
    $db->query("UPDATE `mgr_materialgruppen` SET `mgr_bestellposition`=".$position." WHERE `mgr_bestellposition` = ".($position+1));
    $db->query("UPDATE `mgr_materialgruppen` SET `mgr_bestellposition`=".($position+1)." WHERE `mgr_id` = ".$object->mgr_id);

	$db->close();
  }

  function GruppeLoeschen($position) {
	$db = connectDB();

	// Positionierung des gelöschten Eintrag löschen
    $db->query("UPDATE `mgr_materialgruppen` SET `mgr_bestellposition`= NULL WHERE `mgr_bestellposition`=".$position);
	
	// Nachfolgende Einträge nach hinten schieben
    $resultat = $db->query("SELECT * FROM `mgr_materialgruppen` WHERE mgr_bestellposition > ".$position." ORDER BY mgr_bestellposition DESC");
    while ($object = $resultat->fetch_object()) {
      $db->query("UPDATE `mgr_materialgruppen` SET `mgr_bestellposition`=".($object->mgr_bestellposition-1)." WHERE `mgr_id` = ".$object->mgr_id);
	}

	$db->close();
  }
}
?>