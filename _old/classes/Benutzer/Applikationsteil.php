<?php

class Applikationsteil {

  private $id;
  private $bezeichnung;
  private $lesen;
  private $schreiben;
  private $loeschen;

  function setId($id)                   { $this->id = $id;                   }
  function getId()                      { return $this->id;                  }
	
  function setBezeichnung($bezeichnung) { $this->bezeichnung = $bezeichnung; }
  function getBezeichnung()             { return $this->bezeichnung;         }
	
  function setLesen($lesen)             { $this->lesen = $lesen;             }
  function getLesen()                   { return $this->lesen;               }
	
  function setSchreiben($schreiben)     { $this->schreiben = $schreiben;     }
  function getSchreiben()               { return $this->schreiben;           }
	
  function setLoeschen($loeschen)       { $this->loeschen = $loeschen;       }
  function getLoeschen()                { return $this->loeschen;            }
  
}
?>