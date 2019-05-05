<?php
class MaterialLog {
    
  function Materialliste($materialgruppe) {
    $db = connectDB();
    $materialliste = new Liste();
    $res = $db->query("SELECT * FROM `mat_material` LEFT JOIN `mgr_materialgruppen` ON mat_gruppe = mgr_id WHERE mat_gruppe = $materialgruppe ORDER BY `mat_bezeichnung`");
    while ($mat = $res->fetch_object()) {
      $material = new Material();
      $material->setId($mat->mat_id);				
      $material->setBezeichnung($mat->mat_bezeichnung);   
      $material->setBestand($mat->mat_bestand);            
      $material->setAlltagsbestand($mat->mat_alltagsbestand);  
      $material->setReparatur($mat->mat_reparatur);  
      $material->setMaterialgruppe($mat->mat_gruppe);  
      $material->setMaterialgruppeBez($mat->mgr_bezeichnung);  
      $material->setPackgroesse($mat->mat_packgroesse);
      $materialliste->eintrag_hinzufuegen($material);
    }
    $db->close();
	return $materialliste;
  }

  function MaterialSpeichern($material) {
	$db = connectDB();
	if ($material['bezeichnung'] != "") {  
	  if ($material['id'] == "") {  
      $sql = "INSERT INTO `mat_material`(`mat_bezeichnung`, `mat_bestand`, `mat_alltagsbestand`, `mat_reparatur`, `mat_gruppe`, `mat_packgroesse`) VALUES ('".$material['bezeichnung']."','".$material['bestand'].
	         "','".$material['alltagsbestand'].
                   "','".$material['reparatur']."','".$material['gruppe']."','".$material['packgroesse']."')";
	    $db->query($sql); 
	  } else {
        $sql = "UPDATE `mat_material` SET `mat_bezeichnung`='".$material['bezeichnung']."',`mat_bestand`='".$material['bestand']."',`mat_alltagsbestand`='".$material['alltagsbestand'].
                   "',`mat_reparatur`='".$material['reparatur']."',`mat_gruppe`='".$material['gruppe']."',`mat_packgroesse`='".$material['packgroesse']."' WHERE `mat_id`=".$material['id'];
	 $db->query($sql); }
	}
    $db->close();
  }

  function MaterialLoeschen($id) {
	$db = connectDB();
    $sql = "DELETE FROM `mat_material` WHERE `mat_id`=$id";
	$db->query($sql); 
    $db->close();
  }

} 
?>