<?php

class Einheit {
  private $id;
  private $bezeichnung;
  private $uebergeordnet;
  private $bestellen;
  
  function setId($id)                         { $this->id = $id;                         }
  function getId()                            { return $this->id;                        }
	
  function setBezeichnung($bezeichnung)       { $this->bezeichnung = $bezeichnung;       }
  function getBezeichnung()                   { return $this->bezeichnung;               }
	
  function setUebergeordnet($uebergeordnet)   { $this->uebergeordnet = $uebergeordnet;     }
  function getUebergeordnet()                 { return $this->uebergeordnet;              }
	
  function setBestellen($bestellen) { $this->bestellen = $bestellen; }
  function getBestellen()                { return $this->bestellen;            }
}
?>