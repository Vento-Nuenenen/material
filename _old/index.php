<?php
/*************************************************************************************************************************************
 * Initialisierung                                                                                                                   *
 ************************************************************************************************************************************/

include_once("classes/Output/Mitteilung.php");
include_once("classes/Output/OutputGui.php");
include_once("classes/Output/OutputLog.php");
include_once("classes/Benutzer/BenutzerGui.php");
include_once("classes/Benutzer/BenutzerLog.php");
include_once("include/Navigation.php");

session_start();

$outputGui = new OutputGui();
$outputLog = new OutputLog();
$benutzerGui = new BenutzerGui();
$benutzerLog = new BenutzerLog();
$nav = new Navigation();

unset($_SESSION["error"]);
/*************************************************************************************************************************************
 * Verarbeiten Der Benutzeranmeldung                                                                                                *
 *************************************************************************************************************************************/
 
if ($_GET['action'] == "Anmelden") {
  $benutzer = $benutzerLog->BenutzerAnmelden($_POST['benutzername'], $_POST['passwort']);
  if ($benutzer != NULL) {
    $_SESSION['benutzer'] = $benutzer;
    if (isset($_SESSION['redirect'])) {
      $redirect =  $_SESSION['redirect'];
      unset($_SESSION['redirect']);  
      echo "<meta http-equiv='refresh' content='0; url=http://$redirect' />";  
    }
  }
}

if ($_GET['action'] == "PasswortZuruecksetzen") {
  if ($_POST['Zurücksetzen'] == "Zurücksetzen") {
    $benutzerLog->PasswortZuruecksetzen($_POST['benutzername']);
  }
}

if ($_GET['action'] == "PasswortSpeichern") {
  if ($_POST['Speichern'] == "Speichern") {
    $_SESSION['benutzer'] = $benutzerLog->PasswortSpeichern($_POST);
  }
}


if ($_GET['action'] == "logout") {
  $benutzerLog->Abmelden();	
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Index</title>
  <link href="include/screen.css" rel="stylesheet" type="text/css" media="screen" />
  <link href="include/event_screen.css" rel="stylesheet" type="text/css" media="screen" />

	<link rel="stylesheet" type="text/css" href="kalender/tcal.css" />
	<script type="text/javascript" src="kalender/tcal.js"></script> 

</head>

<body>
<table width="100%" height="100%" id="body">
  <tr height="150">
    <td id="header">&nbsp;</td>
    <td width="950" id="header"><a href="http://www.pfadi-nuenenen.ch"><img src="img/Logo-Nuenenen-fa3.png" width="727" height="120"/></a></td>
    <td id="header">&nbsp;</td>
  </tr>
  <tr height="40">
    <td id="header">&nbsp;</td>
    <td id="header">
    
    <?php
	$nav->TopNavAnzeigen("home");
        ?>
    
    
    
    </td>
    <td id="header">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td valign="top">

  <table cellspacing="0" height="500">
    <colgroup>
      <col width="200">
      <col width="750">
    </colgroup>
    <tr>
      <td id="content" colspan="2">
        <?php
        if (!isset($_SESSION['benutzer'])) {
		  if ($_GET['seite'] == "PasswortVergessen"){
		    $benutzerGui->PasswortVergessen();
		  } else {		
		    $benutzerGui->BenutzerAnmeldung();
		  }
		} else {
		  if ($_SESSION['benutzer']->getInitial()) {
		    $benutzerGui->PasswortAendern($_SESSION['benutzer']->getId());
		  } else { 
           /*************************************************************************************************************
            * Seiteninhalt                                                                                              *
            *************************************************************************************************************/?>
           <h1>Willkommen in der Materialverwaltung der Abteilung Nünenen</h1>
           <p>Hier könnt ihr euer Material fürs n&auml;chste Weekend, das n&auml;chste Lager, oder auch einfach die n&auml;chste Aktivit&auml;t
              bestellen. Das Material und die Bestellmengen werden fortlaufend aktualisiert und den aktuellen Gegebenheiten angepasst. So 
              sollte das bestellte Material auch immer verf&uuml;gbar sein.
           <p>&nbsp;</p>
           <h2>Aktuell</h2>
           <?php 
           $meldungen = $outputLog->AktuelleMeldungen();
           $outputGui->MitteilungsListe($meldungen, FALSE); 
           ?>
           
           <?php
		  }
		}
		  echo "<p class='error'>".$_SESSION['error']."</p>";
        ?>
      </td>
    </tr>
  </table>

      </td>
    <td>&nbsp;</td>
    </tr>
  <tr height="100">
    <td id="header">&nbsp;</td>
    <td id="header">&nbsp;</td>
    <td id="header">&nbsp;</td>
  </tr>
</table>



</body>
</html>