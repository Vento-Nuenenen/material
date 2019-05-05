<?php
/*************************************************************************************************************************************
 * Initialisierung                                                                                                                   *
 *************************************************************************************************************************************/

include_once("classes/Benutzer/BenutzerGui.php");
include_once("classes/Benutzer/BenutzerLog.php");
include_once("classes/Bestellungen/Bestellung.php");
include_once("classes/Bestellungen/BestellungenGui.php");
include_once("classes/Bestellungen/BestellungenLog.php");
include_once("classes/Material/MaterialgruppenGui.php");
include_once("classes/Material/MaterialgruppenLog.php");
include_once("include/Navigation.php");

session_start();

// Redirect aufs Anmeldeformular wenn der Benutzer nicht angemeldet ist
if (!isset($_SESSION['benutzer'])) {
    $url = $_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
    $keys = array_keys($_GET);
    for ($i=0; $i< count($_GET); $i++) {
      if ($i == 0) {
        $url .= "?".$keys[$i]."=".$_GET[$keys[$i]];   
      } else {
        $url .= "&".$keys[$i]."=".$_GET[$keys[$i]];   
      }
    }
  $_SESSION['redirect'] = $url;
  echo "<meta http-equiv='refresh' content='0; url=index.php' />";  
}

$benutzerGui = new BenutzerGui();
$benutzerLog = new BenutzerLog();
$bestellungenGui = new BestellungenGui();
$bestellungenLog = new BestellungenLog();
$materialgruppenGui = new MaterialgruppenGui();
$materialgruppenLog = new MaterialgruppenLog();
$nav = new Navigation();

unset($_SESSION["error"]);

if ($_GET['seite'] != "MaterialBestellen") {
  unset($_SESSION["bestellung"]);
}

//$bestellungenLog->Bestellung(1);

/*************************************************************************************************************************************
 * Verarbeiten von eingegebenen Daten                                                                                                *
 *************************************************************************************************************************************/
// Materialbestellung
if ($_GET['action'] == "DatumSpeichern") {
  $bestellungenLog->GrunddatenPruefen($_POST);
}

if ($_GET['action'] == "BestellungSpeichern") {
  $_SESSION['bestellung'] = $bestellungenLog->BestellungSpeichern($_POST);
}
if ($_GET['action'] == "BestellungLoeschen") {
  $bestellungenLog->BestellungLoeschen($_GET['id']);
}

if ($_GET['action'] == "BestellungBestaetigen") {
  $bestellungenLog->BestellungWeiterleiten($_GET['id']);
}
if (isset($_SESSION['benutzer'])) {
  $bestellungen = $bestellungenLog->BestellungenListe($_SESSION['benutzer']->getId()); // todo:
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
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
	$nav->TopNavAnzeigen("bestellen");
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
      <td id="subnav">
        <?php
		$nav->BestellNavAnzeigen();
		?>
      </td>
      <td id="content">
        <?php
		  if ($_GET['seite'] == "MaterialBestellen"){
			if (isset($_GET['index'])) {
			  $bestellung = $bestellungen->eintrag_mit_index($_GET['index']);
			} else {
			  $bestellung = NULL;
		    }
			$bestellungenGui->Materialbestellung($bestellung);
		  } else if ($_GET['seite'] == "MeineBestellungen") {
			$bestellungenGui->BestellungenListe($bestellungen);
		  } else if ($_GET['seite'] == "BestellungAnzeigen") {
			$bestellungenGui->BestellungAnzeigen($bestellungen->eintrag_mit_index($_GET['index']));
		  } else if ($_GET['seite'] == "BestellungLoeschen") {
			$bestellungenGui->BestellungLoeschen($bestellungen->eintrag_mit_index($_GET['index']));
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
