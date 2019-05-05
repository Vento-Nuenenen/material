<?php
class Mail {
	
  function EinladungBenutzer($benutzer) {
	// eMail Inhalt
	$mailtxt  = "<html>";
	$mailtxt .= "<head>";
	$mailtxt .= "<title>Login Materialverwaltung Nünenen</title>";
	$mailtxt .= "<style>";
	$mailtxt .= "body { font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }";
	$mailtxt .= "table { font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }";
	$mailtxt .= "th{ background:#999999; font-weight: bold; text-align: left; }";
	$mailtxt .= "</style>";
	$mailtxt .= "</head>";
	  
	$mailtxt .= "<body>";
	$mailtxt .= "<p><b>Login Materialverwaltung Nünenen:</b></p>";
	$mailtxt .= "<p>Mit dieser Nachricht erhälst du deinen Benutzernamen für die Materialverwaltung der Abteilung Nünenen. Dein initiales Passwort wird dir in einer separaten Nachricht zugestellt. Bei der ersten 
	             Anmeldung wirst du aufgefordert ein eigenes Passwort zu setzen.</p>";
	$mailtxt .= "<table>";
	$mailtxt .= "<tr><td width='120'>Benutzername:</td><td>".$benutzer->getBenutzername()."</td></tr>";
	$mailtxt .= "<tr><td>eMail:</td><td>".$benutzer->getEmail()."</td></tr>";
	$mailtxt .= "<tr><td>Link:</td><td><a href='http://www.pfadi-nuenenen.ch/material'>www.pfadi-nuenenen.ch/material</a></td></tr>";
	$mailtxt .= "</table>";
    $mailtxt .= "</body>";
	$mailtxt .= "</html>";
	  
	mail ($benutzer->getEmail() ,"Login Materialverwaltung Nünenen" , $mailtxt, $this->Header());
  }

  function RuecksetzungPasswort($passwort, $email) {
	// eMail Inhalt
	$mailtxt  = "<html>";
	$mailtxt .= "<head>";
	$mailtxt .= "<title>Neues Passwort Materialverwaltung Nünenen</title>";
	$mailtxt .= "<style>";
	$mailtxt .= "body { font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }";
	$mailtxt .= "table { font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }";
	$mailtxt .= "th{ background:#999999; font-weight: bold; text-align: left; }";
	$mailtxt .= "</style>";
	$mailtxt .= "</head>";
	  
	$mailtxt .= "<body>";
	$mailtxt .= "<p><b>Neues Passwort Materialverwaltung Nünenen:</b></p>";
	$mailtxt .= "<p>Mit dieser Nachricht erhälst du dein neues Passwort für die Materialverwaltung der Abteilung Nünenen. Bei der ersten 
	             Anmeldung wirst du aufgefordert ein eigenes Passwort zu setzen.</p>";
	$mailtxt .= "<table>";
	$mailtxt .= "<tr><td width='120'>Passwort:</td><td>".$passwort."</td></tr>";
	$mailtxt .= "</table>";
    $mailtxt .= "</body>";
	$mailtxt .= "</html>";
	  
	mail ($email ,"Neues Passwort Materialverwaltung Nünenen" , $mailtxt, $this->Header());
  }

  function AenderungBenutzername($user, $email) {
	// eMail Inhalt
	$mailtxt  = "<html>";
	$mailtxt .= "<head>";
	$mailtxt .= "<title>Neuer Benutzername Materialverwaltung Nünenen</title>";
	$mailtxt .= "<style>";
	$mailtxt .= "body { font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }";
	$mailtxt .= "table { font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }";
	$mailtxt .= "th{ background:#999999; font-weight: bold; text-align: left; }";
	$mailtxt .= "</style>";
	$mailtxt .= "</head>";
	  
	$mailtxt .= "<body>";
	$mailtxt .= "<p><b>Neuer Benutzername Materialverwaltung Nünenen:</b></p>";
	$mailtxt .= "<p>Dein Benutzername für die Materialverwaltung wurde geändert.</p>";
	$mailtxt .= "<table>";
	$mailtxt .= "<tr><td width='120'>Neuer Benutzername:</td><td>".$user."</td></tr>";
	$mailtxt .= "</table>";
    $mailtxt .= "</body>";
	$mailtxt .= "</html>";
	  
	mail ($email ,"Neues Passwort Materialverwaltung Nünenen" , $mailtxt, $this->Header());
  }
  
  function WeiterleitungBestellung($bst) {
	// eMail Inhalt
	$mailtxt  = "<html>";
	$mailtxt .= "<head>";
	$mailtxt .= "<title>Neue Bestellung eingegangen</title>";
	$mailtxt .= "<style>";
	$mailtxt .= "body { font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }";
	$mailtxt .= "table { font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }";
	$mailtxt .= "th{ background:#999999; font-weight: bold; text-align: left; }";
	$mailtxt .= "</style>";
	$mailtxt .= "</head>";
	  
	$mailtxt .= "<body>";
	$mailtxt .= "<p><b>Eine neue Bestellung ist eingegangen:</b></p>";
	$mailtxt .= "<table>";
	$mailtxt .= "<tr><td width='100'>Einheit:</td><td>".$bst->getEinheitBez()."</td></tr>";
	$mailtxt .= "<tr><td>Datum:</td><td>".date("d.m.Y", $bst->getVon())." - ".date("d.m.Y", $bst->getBis())."</td></tr>";
	$mailtxt .= "<tr><td>Anlass:</td><td>".$bst->getAnlass()."</td></tr>";
	$mailtxt .= "</table>";
	$mailtxt .= "<br/>";
	$mailtxt .= "<table>";
	$mailtxt .= "<th colspan='2'>Bestelltes Material:</th>";
	$materialliste = $bst->getMatbestellung();
    $materialliste->index_setzen(-1);
    while (!$materialliste->listenende()) {
      $material = $materialliste->naechster_eintrag(); 
	  if ($materialliste->aktueller_index()%2 != 0) {
        $mailtxt .= "<tr style='background-color: #DDD;'><td width='300'>".$material->getBezeichnung()."</td><td align='right'>".$material->getBestellmenge()."</td></tr>";
	  } else {
        $mailtxt .= "<tr><td width='300'>".$material->getBezeichnung()."</td><td align='right'>".$material->getBestellmenge()."</td></tr>";
	  }
    }
	$mailtxt .= "</table>";
	$mailtxt .= "<br/>";
 	$mailtxt .= "<p>Bestellung annehmen: <a href='http://www.pfadi-nuenenen.ch/material/verwalten.php'>www.pfadi-nuenenen.ch/material</a></p>";
    $mailtxt .= "</body>";
	$mailtxt .= "</html>";
	  
	mail ("material@pfadi-nuenenen.ch" ,"Neue Bestellung eingegangen" , $mailtxt, $this->Header());
    
  }

  private function Header() {
	//Mail Headers
	$header  = "MIME-Version: 1.0'\r\n";
	$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$header .= "From: material@pfadi-nuenenen.ch\r\n";
	$header .= "Reply-To: material@pfadi-nuenenen.ch\r\n";
    return $header;
  }
}
?>