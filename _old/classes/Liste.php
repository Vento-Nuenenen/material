<?php

class Liste {

  private $liste;
  private $index;

  public function __construct() {
    $this->liste = array();
    $this->index = 0;
  }
  
  function eintrag_hinzufuegen($eintrag) { 
    $this->liste[] = $eintrag;
  }
  
  function aktueller_eintrag() { 
    return $this->liste[$this->index];
  }

  function letzter_eintrag() { 
    return $this->liste[--$this->index];
  }

  function naechster_eintrag() { 
    return $this->liste[++$this->index];
  }

  function eintrag_mit_index($index) { 
    return $this->liste[$index];
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
    return $this->index+1 >= count($this->liste);
  }

  function anzahl_eintraege() {
    return count($this->liste);
  }

  function eintrag_hinzufuegen_id($id, $eintrag) { 
    $this->liste[$id] = $eintrag;
  }

  function eintrag_mit_id($id) { 
    return $this->liste[$id];
  }
}
?>