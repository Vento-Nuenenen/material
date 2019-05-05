<?php
class Bestellung{
	
  private $id;
  private $einheit;
  private $einheitBez;
  private $kontakt;
  private $kontaktBez;
  private $anlass;
  private $von;
  private $bis;
  private $status;
  private $matbestellung;
  
  public function __construct() {
	$this->von = strtotime(date("d.m.Y"))+7*24*60*60;
	$this->bis = strtotime(date("d.m.Y"))+7*24*60*60;  
  }
  
  public function setId($id)						{$this->id = $id;					      }
  public function getId()							{return $this->id;					      }

  public function setEinheit($einheit)	            {$this->einheit = $einheit;	              }
  public function getEinheit()					    {return $this->einheit;		              }

  public function setEinheitBez($einheitBez)	    {$this->einheitBez = $einheitBez;	      }
  public function getEinheitBez()					{return $this->einheitBez;		          }

  public function setKontakt($kontakt)	            {$this->kontakt = $kontakt;	              }
  public function getKontakt()					    {return $this->kontakt;		              }

  public function setKontaktBez($kontaktBez)        {$this->kontaktBez = $kontaktBez;         }
  public function getKontaktBez()				    {return $this->kontaktBez;		          }

  public function setAnlass($anlass)	            {$this->anlass = $anlass;	 		      }
  public function getAnlass()	  				    {return $this->anlass;		              }

  public function setVon($von)	                    {$this->von = $von;	 		              }
  public function getVon()	  				        {return $this->von;		                  }

  public function setBis($bis)	                    {$this->bis = $bis;	                      }
  public function getBis()				            {return $this->bis;		                  }

  public function setStatus($status)	            {$this->status = $status;	              }
  public function getStatus()				        {return $this->status;		              }

  public function setMatbestellung($matbestellung)	{$this->matbestellung = $matbestellung;	  }
  public function getMatbestellung()				{return $this->matbestellung;		      }

}
?>