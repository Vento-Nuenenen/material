<?php

class Rolle {
  private $id;
  private $bezeichnung;
  private $beschreibung;
  private $berechtigungen;
  private $einheiten;
  private $benutzer;
  
  function setId($id)                         { $this->id = $id;                         }
  function getId()                            { return $this->id;                        }
	
  function setBezeichnung($bezeichnung)       { $this->bezeichnung = $bezeichnung;       }
  function getBezeichnung()                   { return $this->bezeichnung;               }
	
  function setBeschreibung($beschreibung)     { $this->beschreibung = $beschreibung;     }
  function getBeschreibung()                  { return $this->beschreibung;              }
	
  function setBerechtigungen($berechtigungen) { $this->berechtigungen = $berechtigungen; }
  function getBerechtigungen()                { return $this->berechtigungen;            }

  function setEinheiten($einheiten)           { $this->einheiten = $einheiten;           }
  function getEinheiten()                     { return $this->einheiten;                 }

  function setBenutzer($benutzer)             { $this->benutzer = $benutzer;             }
  function getBenutzer()                      { return $this->benutzer;                  }
}
?>