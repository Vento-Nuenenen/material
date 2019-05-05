<?php
/*************************************************************************************************************************************
 * Initialisierung                                                                                                                   *
 *************************************************************************************************************************************/

include_once("classes/Material/Material.php");
include_once("classes/Material/MaterialGui.php");
include_once("classes/Material/MaterialLog.php");
include_once("classes/Material/MaterialgruppenGui.php");
include_once("classes/Material/MaterialgruppenLog.php");
include_once("classes/Liste.php");
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

$materialGui = new MaterialGui();
$materialLog = new MaterialLog();
$materialgruppenGui = new MaterialgruppenGui();
$materialgruppenLog = new MaterialgruppenLog();
$nav = new Navigation();

unset($_SESSION["error"]);


/*************************************************************************************************************************************
 * Verarbeiten von eingegebenen Daten                                                                                                *
 *************************************************************************************************************************************/
// Material
if ($_GET['action'] == "MaterialSpeichern") {
  if ($_POST['Speichern'] == "Speichern") {
    $materialLog->MaterialSpeichern($_POST);
  }
}
if ($_GET['action'] == "MaterialLoeschen") {
  if ($_POST['Löschen'] == "Löschen") {
    $materialLog->MaterialLoeschen($_POST['id']);
  }
}


// Materialgruppen
if ($_GET['action'] == "MaterialgruppeSpeichern") {
  if ($_POST['Speichern'] == "Speichern") {
    $materialgruppenLog->MaterialgruppeSpeichern($_POST);
  }
}
if ($_GET['action'] == "MaterialgruppeLoeschen") {
  if ($_POST['Löschen'] == "Löschen") {
    $materialgruppenLog->MaterialgruppeLoeschen($_POST);
  }
}

// Bestellformular
if ($_GET['action'] == "GruppeHinzufuegen") {
  if ($_POST['Speichern'] == "Speichern") {
    $materialgruppenLog->GruppeHinzufuegen($_POST);
  }
}
if ($_GET['action'] == "GruppeAuf") {
  $materialgruppenLog->GruppeAuf($_GET['pos']);
}
if ($_GET['action'] == "GruppeAb") {
  $materialgruppenLog->GruppeAb($_GET['pos']);
}
if ($_GET['action'] == "GruppeLoeschen") {
  $materialgruppenLog->GruppeLoeschen($_GET['pos']);
}
 
/*************************************************************************************************************************************
 * Grunddaten aufrufen                                                                                                               *
 *************************************************************************************************************************************/
 
 
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
	  <?php $nav->TopNavAnzeigen("stammdaten");  ?>
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
		$nav->StammdatenNavAnzeigen();
		?>
      </td>
      <td id="content">  <?php
        if ($_GET['seite'] == "Material"){
          $matgruppen = $materialgruppenLog->MaterialgruppenListe();
          if (isset($_GET['matgruppe'])){
            $grp = $_GET['matgruppe'];
          } else {
            $grp = $matgruppen->eintrag_mit_index(0)->getId();     
          }
		   $materialGui->MateriallisteAnzeigen($materialgruppenLog->MaterialgruppenListe(), $materialLog->Materialliste($grp));
		 } else if ($_GET['seite'] == "Materialgruppen"){
		   $materialgruppenGui->Materialgruppen($materialgruppenLog->MaterialgruppenListe());
		 } else if ($_GET['seite'] == "Bestellformular") {
          $materialgruppenGui->Bestellformular($materialgruppenLog->MaterialgruppenNichtInBestellformular(), $materialgruppenLog->MaterialgruppenBestellformular());
		 }
		 echo "<p class='error'>$error</p>";  ?>
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
