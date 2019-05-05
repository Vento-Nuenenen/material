<?php
class Berechtigungen {

  private $berechtigungen;
  private $index;

  function Berechtigungen() {
    $this->berechtigungen = array();
    $this->index = 0;
  }
  
  function applikationsteil_hinzufuegen($applikationsteil) { 
     $this->berechtigungen[] = $applikationsteil;
  }

  function aktueller_eintrag() { 
    return $this->berechtigungen[$this->index];
  }

  function letzter_eintrag() { 
    return $this->berechtigungen[--$this->index];
  }

  function naechster_eintrag() { 
    return $this->berechtigungen[++$this->index];
  }

  function eintrag_mit_index($index) { 
    return $this->berechtigungen[$index];
  }
  
  function index_setzen($index) {
    $this->index = $index;
  }
  
  function aktueller_index() {
    return $this->index;
  }

  function index_reset() {
    $this->index = 0;
  }

  function listenanfang() {
    return $this->index-1 < 0;
  }

  function listenende() {
    return $this->index+1 >= count($this->berechtigungen);
  }

  function anzahl_eintraege() {
    return count($this->berechtigungen);
  }
}
?>