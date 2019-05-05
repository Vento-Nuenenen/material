<?php
class Material{
	
  private $id;
  private $bezeichnung;
  private $bestand;
  private $alltag;
  private $verbrauchsmaterial;
  private $reparatur;
  private $ausgeliehen;
  private $matgruppe;
  private $matgruppeBez;
  private $packgroesse;
  private $bestellmenge;
  private $bestaetigtemenge;
  private $ausgabemenge;
  private $rueckgabemenge;
  private $rueckgabereparatur;
  private $rueckgabedefekt;
  
  public function setId($id)							        {$this->id = $id;					              }
  public function getId()								        {return $this->id;					              }

  public function setBezeichnung($bezeichnung)	    	        {$this->bezeichnung = $bezeichnung;	              }
  public function getBezeichnung()						        {return $this->bezeichnung;		                  }

  public function setBestand($bestand)	            	        {$this->bestand = $bestand;	 		              }
  public function getBestand()	  				    	        {return $this->bestand;		                      }

  public function setAlltagsbestand($alltag)	    	        {$this->alltag = $alltag;	                      }
  public function getAlltagsbestand()					        {return $this->alltag;		                      }
    
  public function setVerbrauchsmaterial($verbrauchsmaterial)	{$this->verbrauchsmaterial = $verbrauchsmaterial; }
  public function getVerbrauchsmaterial()				        {return $this->verbrauchsmaterial;		          }

  public function setReparatur($reparatur)	                    {$this->reparatur = $reparatur;	                  }
  public function getReparatur()				                {return $this->reparatur;		                  }

  public function setAusgeliehen($ausgeliehen)	                {$this->ausgeliehen = $ausgeliehen;	              }
  public function getAusgeliehen()				                {return $this->ausgeliehen;		                  }

  public function setMaterialgruppe($matgruppe)	                {$this->matgruppe = $matgruppe;	                  }
  public function getMaterialgruppe()				            {return $this->matgruppe;		                  }

  public function setMaterialgruppeBez($matgruppeBez)	        {$this->matgruppeBez = $matgruppeBez;	          }
  public function getMaterialgruppeBez()				        {return $this->matgruppeBez;		              }

  public function setPackgroesse($packgroesse)	                {$this->packgroesse = $packgroesse;	              }
  public function getPackgroesse()				                {return $this->packgroesse;		                  }

  public function setBestellmenge($bestellmenge)	            {$this->bestellmenge = $bestellmenge;             }
  public function getBestellmenge()				                {return $this->bestellmenge;		              }
    
  public function setBestaetigtemenge($bestaetigtemenge)	    {$this->bestaetigtemenge = $bestaetigtemenge;     }
  public function getBestaetigtemenge()				            {return $this->bestaetigtemenge;		          }

  public function setAusgabemenge($ausgabemenge)	            {$this->ausgabemenge = $ausgabemenge;             }
  public function getAusgabemenge()				                {return $this->ausgabemenge;		              }

  public function setRueckgabemenge($rueckgabemenge)	        {$this->rueckgabemenge = $rueckgabemenge;         }
  public function getRueckgabemenge()				            {return $this->rueckgabemenge;		              }
    
  public function setRueckgabeReparatur($rueckgabereparatur)	{$this->rueckgabereparatur = $rueckgabereparatur; }
  public function getRueckgabeReparatur()				        {return $this->rueckgabereparatur;		          }
    
  public function setRueckgabeDefekt($rueckgabedefekt)	        {$this->rueckgabedefekt = $rueckgabedefekt;       }
  public function getRueckgabeDefekt()				            {return $this->rueckgabedefekt;		              }

}
?>