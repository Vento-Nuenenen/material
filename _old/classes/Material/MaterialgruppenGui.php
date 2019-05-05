<?php

class MaterialgruppenGui{

/*************************************************************************************************************************************
 * Funktionen                                                                                                       *
 *************************************************************************************************************************************/

  function Materialgruppen($materialgruppen) {
	if (isset($_GET['index'])){
	  $gruppe = $materialgruppen->eintrag_mit_index($_GET['index']);
	  $id = $gruppe->getId();
	  $bezeichnung = $gruppe->getBezeichnung();
	} else {
	  $id = "";
	  $bezeichnung = "";
	}
    ?>
    <h1>Materialgruppen</h1>
    <table id="mat">
      <col width="170">
      <col width="110">
      <col width="40">
      <tr>
        <th>Bezeichnung:</th>
        <th colspan="2"></th>
      </tr>
      <?php if ($_GET['action'] == "loeschen") { ?>
        <form action="?seite=Materialgruppen&action=MaterialgruppeLoeschen" method="post">
          <tr>
            <td><input type="hidden" name="id" value="<?php echo $id; ?>" /><?php echo $bezeichnung; ?></td>
            <td colspan="2"><input type="submit" name="L&ouml;schen" value="L&ouml;schen" /><input type="submit" name="Abbrechen" value="Abbrechen" /></td>
          </tr>
        </form>
      <?php } else {?>
        <form action="?seite=Materialgruppen&action=MaterialgruppeSpeichern" method="post">
          <tr>
            <td><input type="hidden" name="id" value="<?php echo $id; ?>" /><input type="text" name="bezeichnung" value="<?php echo $bezeichnung; ?>" size="30" maxlength="50"/></td>
            <td colspan="2"><input type="submit" name="Speichern" value="Speichern" /><input type="submit" name="Abbrechen" value="Abbrechen" /></td>
          </tr>
        </form>
      <?php } ?>
      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <th>Bezeichnung:</th>
        <th>Anzahl in Gruppe:</th>
        <th></th>
      </tr>
	  <?php
	  $materialgruppen->index_setzen(-1);
      while (!$materialgruppen->listenende()) {
        $gruppe = $materialgruppen->naechster_eintrag(); ?>
        <tr <?php if ($materialgruppen->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
          <td><?php echo $gruppe->getBezeichnung(); ?></td>
          <td align="center"><?php echo $gruppe->getAnzahl(); ?></td>
          <td>
            <a href="?seite=Materialgruppen&action=bearbeiten&index=<?php echo $materialgruppen->aktueller_index(); ?>"><img src="img/bearbeiten.png" width="10" height="10" border="0"/></a>
            <?php if ($gruppe->getAnzahl() == 0) { ?>
              <a href="?seite=Materialgruppen&action=loeschen&index=<?php echo $materialgruppen->aktueller_index(); ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
    </table>
    <?
  }


  function Bestellformular($materialgruppen, $bestellgruppen) {
	$i = 0;
    ?>
	<h1>Bestellformular</h1>
    <p>Aufbau des Bestellformulars</p>
    <form action="?seite=Bestellformular&action=GruppeHinzufuegen" method="post">
      <table id="mat">
        <col width="250">
        <col width="150">
        <?php
		
		
	    if (isset($_GET['pos']) && $_GET['pos'] == $i && $_GET['action'] == "GruppeEinfuegen"){ ?>
          <tr>
            <td>
              <input type="hidden" name="pos" value="<?php echo $_GET['pos']; ?>" />
              <select name="gruppe">
                <option value="" >&nbsp;</option> <?php
	            $materialgruppen->index_setzen(-1);
                while (!$materialgruppen->listenende()) {
                  $gruppe = $materialgruppen->naechster_eintrag(); ?>
                  <option value="<?php echo $gruppe->getId(); ?>" ><?php echo $gruppe->getBezeichnung(); ?></option>
                <?php } ?>  
              </select>            
            </td>
            <td><input type="submit" name="Speichern" value="Speichern" /><input type="submit" name="Abbrechen" value="Abbrechen" /></td>
          </tr>
	    <?php } else { ?>
        <tr>
          <td><a href="?seite=Bestellformular&action=GruppeEinfuegen&pos=<?php echo $i; ?>"><img src="img/neu.png" width="10" height="10" border="0"/></a></td>
          <td></td>
        </tr>
	    <?php
        }

	    $bestellgruppen->index_setzen(-1);
        while (!$bestellgruppen->listenende()) {
          $gruppe = $bestellgruppen->naechster_eintrag(); ?>
          <tr>
            <td rowspan="3">
              <p style="border-width:thin; border-bottom-color:#333; border-style:solid; padding:5px;">
                <b><?php echo $gruppe->getBezeichnung(); ?></b><br /><?php echo $gruppe->getAnzahl()." Eintr&auml;ge"; ?></p>
            </td>
            <td>
              <?php if ($i != 0) { ?>
                <a href="?seite=Bestellformular&action=GruppeAuf&pos=<?php echo $i; ?>"><img src="img/br_up.png" width="10" height="10" border="0"/></a>
              <?php } ?> &nbsp;
            </td>
          </tr> 
		  <tr><td><a href="?seite=Bestellformular&action=GruppeLoeschen&pos=<?php echo $i; ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a></td></tr>
		  <tr>
            <td>
              <?php if ($i != $bestellgruppen->anzahl_eintraege()-1) { ?>
                <a href="?seite=Bestellformular&action=GruppeAb&pos=<?php echo $i; ?>"><img src="img/br_down.png" width="10" height="10" border="0"/></a>
              <?php } ?> &nbsp;
            </td>
          </tr>
		  <?php
		  $i++;
	      if (isset($_GET['pos']) && $_GET['pos'] == $i && $_GET['action'] == "GruppeEinfuegen"){ ?>
            <tr>
              <td>
                <input type="hidden" name="pos" value="<?php echo $_GET['pos']; ?>" />
                <select name="gruppe">
                  <option value="" >&nbsp;</option> <?php
	              $materialgruppen->index_setzen(-1);
                  while (!$materialgruppen->listenende()) {
                    $gruppe = $materialgruppen->naechster_eintrag(); ?>
                    <option value="<?php echo $gruppe->getId(); ?>" ><?php echo $gruppe->getBezeichnung(); ?></option>
                  <?php } ?>  
                </select>            
              </td>
              <td><input type="submit" name="Speichern" value="Speichern" /><input type="submit" name="Abbrechen" value="Abbrechen" /></td>
            </tr>
	      <?php } else { ?>
          <tr>
            <td><a href="?seite=Bestellformular&action=GruppeEinfuegen&pos=<?php echo $i; ?>"><img src="img/neu.png" width="10" height="10" border="0"/></a></td>
            <td></td>
          </tr>
	      <?php
          }

        } ?>
      </table>
    </form>
	
	<?php  
  }
}
?>