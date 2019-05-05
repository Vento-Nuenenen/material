<?php
include_once("classes/Benutzer/Benutzer.php");
include_once("classes/Benutzer/BenutzerLog.php");
include_once("classes/Benutzer/Berechtigung.php");
include_once("classes/Liste.php");

class Navigation{
  
  // Haubtnavigation (Tabs) anzeigen
  function TopNavAnzeigen($active){  
    $benutzerLog = new BenutzerLog();
    ?>
    <ul id="maintabs">
      <li><p>Materialverwaltung N&uuml;nenen &nbsp; </p></li>  <?php 
      if (isset($_SESSION['benutzer']) && $_SESSION['benutzer']->getInitial()==0){ 
	  ?>
        <li <?php echo ($active=="home" ? "class=\"active\"":""); ?>><a href="index.php">Home</a></li>
        <li <?php echo ($active=="bestellen" ? "class=\"active\"":""); ?>><a href="bestellen.php?seite=MeineBestellungen">Bestellen</a></li><?php
        $ber = $benutzerLog->BerechtigungAbfragen($_SESSION['benutzer']->getId(), 2);
        if ($ber->getLesen()){ 
          // Verwaltung-Tab ?>
          <li <?php echo ($active=="verwalten" ? "class=\"active\"":""); ?>><a href="verwalten.php">Verwalten</a></li><?php
        } else {
          echo "<li id='leer'>&nbsp;</li>";
        }
        $ber = $benutzerLog->BerechtigungAbfragen($_SESSION['benutzer']->getId(), 3);
        if ($ber->getLesen()){ 
          // Verwaltung-Tab ?>
          <li <?php echo ($active=="stammdaten" ? "class=\"active\"":""); ?>><a href="stammdaten.php">Stammd.</a></li><?php
        } else {
          echo "<li id='leer'>&nbsp;</li>";
        }
        $ber = $benutzerLog->BerechtigungAbfragen($_SESSION['benutzer']->getId(), 4);
        if ($ber->getLesen()){ 
          // Verwaltung-Tab ?>
          <li <?php echo ($active=="benutzer" ? "class=\"active\"":""); ?>><a href="benutzer.php">Benutzer</a></li><?php
        } else {
          echo "<li id='leer'>&nbsp;</li>";
        }
        // Platzhalter-Tab ?>
        <li id="leer">&nbsp;</li><?php
        // Logout-Tab ?>
        <li><a href="index.php?action=logout">Logout</a></li>  <?php
      }?>
    </ul><?php 
  }

  function BestellNavAnzeigen() {
    if (isset($_SESSION['benutzer'])){
      ?>
      <ul id="subnavi">
        <li><a href="?seite=MeineBestellungen">Meine Bestellungen</a></li>
        <li><a href="?seite=MaterialBestellen">Material bestellen</a></li>
      </ul>
      <?php
	}
  }

  function VerwaltungNavAnzeigen() {
    if (isset($_SESSION['benutzer'])){
      ?>
      <ul id="subnavi">
        <li><b>Bestellungen</b></li>
        <li><a href="?seite=Neu">&#8226; Neu</a></li>
        <li><a href="?seite=Bestaetigt">&#8226; Best&auml;tigt</a></li>
        <li><a href="?seite=Bereitgestellt">&#8226; Bereitgestellt</a></li>
        <li><a href="?seite=Abgeschlossen">&#8226;  Abgeschlossen</a></li>
        <li>&nbsp;</li>
        <li><a href="?seite=MitteilungenVerwalten">Mitteilungen</a></li>
      </ul>
      <?php
	}
  }

  function StammdatenNavAnzeigen() {
    if (isset($_SESSION['benutzer'])){
      ?>
      <ul id="subnavi">
        <li><a href="?seite=Material">Material</a></li>
        <li><a href="?seite=Materialgruppen">Materialgruppen</a></li>
        <li><a href="?seite=Bestellformular">Bestellformular</a></li>
      </ul>
      <?php
	}
  }

  function BenutzerNavAnzeigen() {
    if (isset($_SESSION['benutzer'])){
      ?>
      <ul id="subnavi">
        <li><a href="?seite=Benutzer">Benutzer</a></li>
        <li><a href="?seite=Rollen">Benutzerrollen</a></li>
      </ul>
      <?php
	}
  }

}
?>