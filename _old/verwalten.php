<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*************************************************************************************************************************************
 * Initialisierung                                                                                                                   *
 *************************************************************************************************************************************/
include_once("classes/Output/Mitteilung.php");
include_once("classes/Output/OutputGui.php");
include_once("classes/Output/OutputLog.php");
include_once("classes/Bestellungen/Bestellung.php");
include_once("classes/Bestellungen/BestellungenGui.php");
include_once("classes/Bestellungen/BestellungenLog.php");
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

$outputGui = new OutputGui();
$outputLog = new OutputLog();
$bestellungenGui = new BestellungenGui();
$bestellungenLog = new BestellungenLog();
$nav = new Navigation();
unset($_SESSION["error"]);


/*************************************************************************************************************************************
 * Verarbeiten von eingegebenen Daten                                                                                                *
 *************************************************************************************************************************************/

if (isset($_GET['action']) && $_GET['action'] == "StatusSetzen") {
  $bestellungenLog->BestellStatus($_GET['id'], $_GET['status']);
}

if (isset($_GET['action']) && $_GET['action'] == "BestellungBestaetigen") {
  if (isset($_POST['bestaetigen']) && $_POST['bestaetigen'] == "Bestellung bestätigen") {
    $bestellungenLog->BestellungBestaetigen($_POST);
  } else if (isset($_POST['zurueckweisen']) && $_POST['zurueckweisen'] == "Bestellung an Einheit zurückweisen") {
      $bestellungenLog->BestellungStatusZuruecksetzen($_POST['bestellung']);
  }  
}

if (isset($_GET['action']) && $_GET['action'] == "MaterialBereitstellen") {
  if (isset($_POST['neu']) && $_POST['neu'] == "+ Artikel hinzufA1gen") {
    $_SESSION['mathinzufuegen'] = $bestellungenLog->BestellungErgaenzen($_POST);
    $_GET['seite'] = "BestellungBereitstellen";  
    $_GET['hinzufuegen'] = TRUE;
  } else if (isset($_POST['bereitgestellt']) && $_POST['bereitgestellt'] == "Material bereitgestellt") {
    $bestellungenLog->BestellungBereitgestellt($_POST);    
  } else if (isset($_POST['zuruecksetzen']) && $_POST['zuruecksetzen'] == "Bestätigung wiederrufen") {
      $bestellungenLog->BestellungStatusZuruecksetzen($_POST['bestellung']);
  }
}

if (isset($_GET['action']) && $_GET['action'] == "BestellungRueckgabe") {
  if (isset($_POST['bestaetigen']) && $_POST['bestaetigen'] == "Rückgabe bestätigen") {
      $bestellungenLog->BestellungRueckgabe($_POST);
  } else if (isset($_POST['speichern']) && $_POST['speichern'] == "Rückgabe zwischenspeichern") {
      $bestellungenLog->BestellungRueckgabeZwischenspeichern($_POST);
  } else if (isset($_POST['zuruecksetzen']) && $_POST['zuruecksetzen'] == "Bereitstellung zurücksetzen") {
      $bestellungenLog->BestellungStatusZuruecksetzen($_POST['bestellung']);
  }
}

if (isset($_GET['action']) && $_GET['action'] == "BestellungAbgeschlossen") {
  if (isset($_POST['zuruecksetzen']) && $_POST['zuruecksetzen'] == "Material-Rückgabe zurücksetzen") {
      $bestellungenLog->BestellungStatusZuruecksetzen($_POST['bestellung']);
  }
}

if (isset($_GET['action']) && $_GET['action'] == "BestellungLoeschen") {
   $bestellungenLog->BestellungLoeschen($_GET['id']); 
}

if (isset($_GET['action']) && $_GET['action'] == "MitteilungSpeichern") {
  if ($_POST['Speichern'] == "Speichern"){
    $outputLog->MeldungSpeichern($_POST);
  }
}

if (isset($_GET['action']) && $_GET['action'] == "MitteilungLoeschen") {
  if ($_POST['LA¶schen'] == "LA¶schen"){
    $outputLog->MeldungLoeschen($_POST['id']);
  }
}

if (isset($_GET['action']) && $_GET['action'] == "AusgabeformularDrucken") {
  $outputGui->Materialausgabe($bestellungenLog->Bestellung($_GET['bestellung']));
}

$meldungen = $outputLog->AktuelleMeldungen(); ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
        <td id="header"> <?php
	      $nav->TopNavAnzeigen("verwalten"); ?>
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
              <td id="subnav"> <?php
		        $nav->VerwaltungNavAnzeigen(); ?>
              </td>
              <td id="content"> <?php
                if (isset($_GET['seite'])){  
                  if ($_GET['seite'] == "Neu") {
                    $bestellungen = $bestellungenLog->BestellungenStatus(1); 
                    $bestellungenGui->BestellungsVerwaltungListe($bestellungen);
                    $_SESSION['adminbestellungen'] = $bestellungen;
                  } else if ($_GET['seite'] == "Bestaetigt") {
                    $bestellungen = $bestellungenLog->BestellungenStatus(2); 
                    $bestellungenGui->BestellungsVerwaltungListe($bestellungen);
                    //$bestellungenGui->BestellungBereitstellen($bestellungen);
                    $_SESSION['adminbestellungen'] = $bestellungen;
                  } else if ($_GET['seite'] == "Bereitgestellt") {
                    $bestellungen = $bestellungenLog->BestellungenStatus(3); 
                    $bestellungenGui->BestellungsVerwaltungListe($bestellungen);
                    $_SESSION['adminbestellungen'] = $bestellungen;
                  } else if ($_GET['seite'] == "Abgeschlossen") {
                    $bestellungen = $bestellungenLog->BestellungenStatus(4); 
                    $bestellungenGui->BestellungsVerwaltungListe($bestellungen);
                    $_SESSION['adminbestellungen'] = $bestellungen;

                  /* Bearbeiten der Bestellungen                                                                             */    
                  } else if ($_GET['seite'] == "BestellungBestaetigen") {
                    $bestellungenGui->BestellungBestaetigen($_SESSION['adminbestellungen']->eintrag_mit_index($_GET['index']));
                  } else if ($_GET['seite'] == "BestellungBereitstellen") {
                    $_SESSION['adminbestellungen'] = $bestellungenLog->BestellungenStatus(2); 
                    if (isset($_GET['hinzufuegen'])) {
                      $hinzufuegen = $_GET['hinzufuegen'];
                    } else {
                      $hinzufuegen = FALSE; 
                    }
                    $bestellungenGui->BestellungBereitstellen($_SESSION['adminbestellungen']->eintrag_mit_index($_GET['index']), $hinzufuegen);
                  } else if ($_GET['seite'] == "BestellungRueckgabe") {
                    $bestellungenGui->BestellungRueckgabe($_SESSION['adminbestellungen']->eintrag_mit_index($_GET['index']));
                  } else if ($_GET['seite'] == "BestellungAbgeschlossen") { 
                    $bestellungenGui->BestellungAbgeschlossen($_SESSION['adminbestellungen']->eintrag_mit_index($_GET['index']));
                  } else if ($_GET['seite'] == "BestellungAnzeigen") {
                    $bestellungenGui->BestellungAnzeigenVerwalten($_SESSION['adminbestellungen']->eintrag_mit_index($_GET['index']));
                  } else if ($_GET['seite'] == "BestellungLoeschen") {
                    $bestellungenGui->BestellungLoeschen($_SESSION['adminbestellungen']->eintrag_mit_index($_GET['index']));    
                      
                      
                  } else if ($_GET['seite'] == "MitteilungenVerwalten") {
                    $outputGui->MitteilungsListe($meldungen, TRUE);
                  } else if ($_GET['seite'] == "MitteilungErfassen") {
                    $outputGui->MitteilungBearbeiten(NULL);
                  } else if ($_GET['seite'] == "MitteilungBearbeiten") {
                    $outputGui->MitteilungBearbeiten($meldungen->eintrag_mit_index($_GET['index']));
                  } else if ($_GET['seite'] == "MitteilungLoeschen") {
                    $outputGui->MitteilungLoeschen($meldungen->eintrag_mit_index($_GET['index']));
                  } else {
                    $bestellungenGui->BestellungenUebersicht();
                  }
                } else {
                  $bestellungenGui->BestellungenUebersicht();
                } ?>
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

