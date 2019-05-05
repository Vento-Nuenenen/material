<?php
/*************************************************************************************************************************************
 * Initialisierung                                                                                                                   *
 *************************************************************************************************************************************/
/*
include_once("classes/Benutzer/BenutzerGui.php");


include_once("classes/Material/MaterialgruppenGui.php");
include_once("classes/Material/MaterialgruppenLog.php");
*/


include_once("classes/Benutzer/BenutzerLog.php");
include_once("classes/Bestellungen/Bestellung.php");
include_once("classes/Bestellungen/BestellungenGui2.php");
include_once("classes/Bestellungen/BestellenLog.php");
include_once("classes/Bestellungen/BestellungenDatenLog.php");

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

$bestellungenGui = new BestellungenGui();
$bestellenLog = new BestellenLog();
$bestellungenDatenLog = new BestellungenDatenLog();

$nav = new Navigation();

/*$benutzerLog = new BenutzerLog();
$materialgruppenGui = new MaterialgruppenGui();
$materialgruppenLog = new MaterialgruppenLog();


unset($_SESSION["error"]);

echo"Debug 1<br>";
if ($_GET['seite'] != "MaterialBestellen") {
  unset($_SESSION["bestellung"]);
}
echo"Debug 2<br>";
//$bestellungenLog->Bestellung(1);*/

/*************************************************************************************************************************************
 * Verarbeiten von eingegebenen Daten                                                                                                *
 *************************************************************************************************************************************/
if ($_GET['action'] == "AnlassUeberpruefen") {
  $r = $bestellenLog->AnlassdatenUebenpruefen($_POST);
  if ($r < 0){
    if ($r == -2){
      $_SESSION['step']="1b";
    } else {
      $_SESSION['step']="1";     
    }
  } else {
    $_SESSION['step']="2";
  }
}

if ($_GET['action'] == "KurzfristigBestaetigen") {
  $r = $bestellenLog->KurzfristigBestaetigen($_POST);
  if ($r < 0){
    if ($r == -2){
      $_SESSION['step']="1b";
    } else {
      $_SESSION['step']="1";     
    }
  } else {
    $_SESSION['step']="2";
  }
}

if ($_GET['action'] == "BestellungErfassen"){
    if (isset($_POST['zurueck'])) {
      if ($_POST['letzteKat']<0){
        $_SESSION['step']="1";
        unset($_SESSION["matkat"]);
      } else {
        $_SESSION['matkat'] = $_POST['letzteKat'];
      }
    } else if (isset($_POST['weiter'])) {
      $_SESSION['matkat'] = $_POST['naechsteKat'];
    } else {
      $a = $bestellungenDatenLog->KategorieBestimmen(array_keys($_POST));
      if ($a>0){
        $_SESSION['matkat'] = $a;
      }
    }
    
    print_r($_POST);

}


// Materialbestellung
/*if ($_GET['action'] == "DatumSpeichern") {
  $bestellungenLog->GrunddatenPruefen($_POST);
}

if ($_GET['action'] == "BestellungSpeichern") {
  $_SESSION['bestellung'] = $bestellungenLog->BestellungSpeichern($_POST);
}
echo"Debug 3<br>"; 
if ($_GET['action'] == "BestellungLoeschen") {
  $bestellungenLog->BestellungLoeschen($_GET['id']);
}

if ($_GET['action'] == "BestellungBestaetigen") {
  $bestellungenLog->BestellungWeiterleiten($_GET['id']);
}
if (isset($_SESSION['benutzer'])) {
  $bestellungen = $bestellungenLog->BestellungenListe($_SESSION['benutzer']->getId()); // todo:
}
echo"Debug 4<br>";*/
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
        echo $_GET['step'];
 		$bestellungenGui->MaterialBestellen(NULL);
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
