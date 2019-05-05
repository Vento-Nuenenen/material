<?php
include_once(dirname(dirname(dirname(__FILE__)))."/include/db.php");
include_once(dirname(dirname(__FILE__))."/Liste.php");
include_once("Mitteilung.php");

class OutputLog{

/*************************************************************************************************************************************
 * Funktionen                                                                                                       *
 *************************************************************************************************************************************/

  function AktuelleMeldungen() {
 	$db = connectDB();
      
    $sql = "SELECT * FROM `akt_aktuell` LEFT JOIN usr_benutzer ON akt_benutzer = usr_id WHERE akt_datum > ".(strtotime(date("d.m.Y H:i:s"))-60*24*60*60);  
    $result = $db->query($sql);
    
	$news = new Liste();
    while ($object = $result->fetch_object()) {
      $aktuell = new Mitteilung();
      $aktuell->setId($object->akt_id);
      $aktuell->setBenutzer($object->usr_id)	;
      $aktuell->setBenutzerBez($object->usr_benutzername);
      $aktuell->setDatum($object->akt_datum);
      $aktuell->setMitteilung($object->akt_mitteilung);
      $news->eintrag_hinzufuegen($aktuell); 
    }
 
    $db->close();
    return $news; 
  }
  
  function MeldungSpeichern($meldung) {
 	$db = connectDB();
    if ($meldung['id'] =="") {
      $sql = "INSERT INTO `akt_aktuell`(`akt_benutzer`, `akt_datum`, `akt_mitteilung`) VALUES ('".$meldung['benutzer']."','".strtotime(date("d.m.Y H:i:s"))."','".$meldung['mitteilung']."')";
      $db->query($sql);
    } else {
	  $sql = "UPDATE `akt_aktuell` SET `akt_mitteilung`='".$meldung['mitteilung']."' WHERE `akt_id`=".$meldung['id'];
      $db->query($sql);
	}
    $db->close();
  }

  function MeldungLoeschen($id) {
 	$db = connectDB();
    $sql = "DELETE FROM `akt_aktuell` WHERE `akt_id` = ".$id;
      $db->query($sql);
    $db->close();
  }
  
}