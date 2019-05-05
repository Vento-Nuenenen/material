<?php

  // Die folgende Funktion stellt die Verbindung zur Datenbank her.
  function connectDB(){ 
    $pwd_ok = 0;
    $db = new mysqli("localhost", "pfadinue_materia", "Riedern$$31", "pfadinue_material");
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }
    return $db;
  }
?>