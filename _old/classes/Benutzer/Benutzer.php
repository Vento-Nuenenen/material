<?php
class Benutzer{
	
  private $id;
  private $benutzername;
  private $name;
  private $vorname;
  private $pfadiname;
  private $email;
  private $aktiv;
  private $initial;
  private $berechtigungen;
  
  public function setId($id)						 {$this->id = $id;					       }
  public function getId()							 {return $this->id;					       }

  public function setBenutzername($benutzername)	 {$this->benutzername = $benutzername;     }
  public function getBenutzername()					 {return $this->benutzername;		       }

  public function setName($name)                     {$this->name = $name;                     }
  public function getName()	                         {return $this->name;                      }

  public function setVorname($vorname)               {$this->vorname = $vorname;               }
  public function getVorname()                       {return $this->vorname;                   }

  public function setPfadiname($pfadiname)           {$this->pfadiname = $pfadiname;           }
  public function getPfadiname()                     {return $this->pfadiname;                 }

  public function setEmail($email)                   {$this->email = $email;                   }
  public function getEmail()                         {return $this->email;                     }

  public function setAktiv($aktiv)                   {$this->aktiv = $aktiv;                   }
  public function getAktiv()                         {return $this->aktiv;	                   }

  public function setInitial($initial)               {$this->initial = $initial;               }
  public function getInitial()				         {return $this->initial;                   }

  public function setBerechtigungen($berechtigungen) {$this->berechtigungen = $berechtigungen; }
  public function getBerechtigungen()				 {return $this->berechtigungen;		       }

}
?>