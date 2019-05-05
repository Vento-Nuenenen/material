<?php
class Mitteilung{
	
  private $id;
  private $benutzer;
  private $benutzerBez;
  private $datum;
  private $mitteilung;
  
  public function setId($id)						{$this->id = $id;					      }
  public function getId()							{return $this->id;					      }

  public function setBenutzer($benutzer)	            {$this->benutzer = $benutzer;	              }
  public function getBenutzer()					    {return $this->benutzer;		              }

  public function setBenutzerBez($benutzerBez)        {$this->benutzerBez = $benutzerBez;         }
  public function getBenutzerBez()				    {return $this->benutzerBez;		          }

  public function setDatum($datum)	            {$this->datum = $datum;	 		      }
  public function getDatum()	  				    {return $this->datum;		              }

  public function setMitteilung($mitteilung)	                    {$this->mitteilung = $mitteilung;	 		              }
  public function getMitteilung()	  				        {return $this->mitteilung;		                  }

}
?>