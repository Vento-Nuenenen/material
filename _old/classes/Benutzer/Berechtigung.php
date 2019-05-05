<?php

class Berechtigung {
  private $applikationsteil;
  private $bezeichnung;
  private $lesen;
  private $schreiben;
  private $loeschen;

  function setApplikationsteil($applikationsteil)                   { $this->applikationsteil = $applikationsteil;                   }
  function getApplikationsteil()                      { return $this->applikationsteil;                  }
	
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