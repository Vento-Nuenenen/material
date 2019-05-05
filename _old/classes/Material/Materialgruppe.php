<?php
class Materialgruppe{
	
  private $id;
  private $bezeichnung;
  private $anzahl;                  // Anzahl "Artikel" in der Gruppe
  private $artikel;
  
  public function setId($id)						{$this->id = $id;					  }
  public function getId()							{return $this->id;					  }

  public function setBezeichnung($bezeichnung)	    {$this->bezeichnung = $bezeichnung;	  }
  public function getBezeichnung()					{return $this->bezeichnung;		      }

  public function setAnzahl($anzahl)	            {$this->anzahl = $anzahl;	          }
  public function getAnzahl()					    {return $this->anzahl;		          }

  public function setArtikel($artikel)	            {$this->artikel = $artikel;	          }
  public function getArtikel()					    {return $this->artikel;		          }

}
?>