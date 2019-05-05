<?php  

class MaterialGui {

  function MateriallisteAnzeigen($materialgruppen, $materialliste) {
          if (isset($_GET['matgruppe'])){
            $grp = $_GET['matgruppe'];
          } else {
            $grp = $materialgruppen->eintrag_mit_index(0)->getId();     
          }
      
    ?>
    <h1>Material</h1>
    <table id="mat">
      <col width="170">
      <col width="120">
      <col width="80">
      <col width="80">
      <col width="80">
      <col width="80">
      <col width="60">
      <tr>
        <th>Bezeichnung:</th>
        <th>Materialgruppe:</th>
        <th>Bestand:</th>
        <th>Alltagsm.:</th>
        <th>in Rep.:</th>
        <th>Packgr.:</th>
        <th></th>
      </tr>
      <?php 
      if (isset($_GET['index'])){
        $material = $materialliste->eintrag_mit_index($_GET['index']);
      } else {
        $material = new Material();
      }
      
      
      if ($_GET['action'] == "loeschen") { ?>
        <form action="?seite=Material&action=MaterialLoeschen&matgruppe=<?php echo $grp; ?>" method="post">
          <tr>
            <td><input type="hidden" name="id" value="<?php echo $material->getId(); ?>" /><?php echo $material->getBezeichnung(); ?></td>
            <td><?php echo $material->getMaterialgruppeBez(); ?></td>
            <td><?php echo $material->getBestand(); ?></td>
            <td><?php echo $material->getAlltagsbestand(); ?></td>
            <td><?php echo $material->getReparatur(); ?></td>
            <td><?php echo $material->getPackgroesse(); ?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="7"><input type="submit" name="L&ouml;schen" value="L&ouml;schen" /><input type="submit" name="Abbrechen" value="Abbrechen" /></td>
          </tr>
        </form>
      <?php } else {?>
        <form action="?seite=Material&action=MaterialSpeichern&matgruppe=<?php echo $grp; ?>" method="post">
          <tr>
            <td><input type="hidden" name="id" value="<?php echo $material->getId(); ?>" /><input type="text" name="bezeichnung" value="<?php echo $material->getBezeichnung(); ?>" size="20" maxlength="50"/></td>
            <td>
              <select name="gruppe">
                <option value="" >&nbsp;</option> <?php
	            $materialgruppen->index_setzen(-1);
                while (!$materialgruppen->listenende()) {
                  $gruppe = $materialgruppen->naechster_eintrag(); ?>
                  <option value="<?php echo $gruppe->getId(); ?>"  <?php if ($gruppe->getId() == $material->getMaterialgruppe()){ echo "selected='selected'"; } ?>><?php echo $gruppe->getBezeichnung(); ?></option>
                <?php } ?>  
              </select>                        
            </td>
            <td><input type="text" name="bestand" value="<?php echo $material->getBestand(); ?>" size="10" maxlength="5"/></td>
            <td><input type="text" name="alltagsbestand" value="<?php echo $material->getAlltagsbestand(); ?>" size="10" maxlength="5"/></td>
            <td><input type="text" name="reparatur" value="<?php echo $material->getReparatur(); ?>" size="10" maxlength="5"/></td>
            <td><input type="text" name="packgroesse" value="<?php echo $material->getPackgroesse(); ?>" size="10" maxlength="5"/></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="7"><input type="submit" name="Speichern" value="Speichern" /><input type="submit" name="Abbrechen" value="Abbrechen" /></td>
          
          </tr>
        </form>
      <?php } ?>
      <tr><td colspan="7">&nbsp;</td></tr>
      <tr>
        <td colspan="7" align="center">  <?php

  	      $materialgruppen->index_setzen(-1);
          while (!$materialgruppen->listenende()) {
            $gruppe = $materialgruppen->naechster_eintrag();
            if ($materialgruppen->aktueller_index() > 0) {
              echo " &bull; ";   
            }
            if ($gruppe->getId() == $grp) {
              echo "<label>".$gruppe->getBezeichnung()."</label>"; 
            } else {
              echo "<a href='?seite=Material&matgruppe=".$gruppe->getId()."' id='link'>".$gruppe->getBezeichnung()."</a>";
            }
          } ?>  
        </td>
      </tr>
      <tr><td colspan="7">&nbsp;</td></tr>
      <tr>
        <th>Bezeichnung:</th>
        <th>Materialgruppe:</th>
        <th>Bestand:</th>
        <th>Alltagsm.:</th>
        <th>in Rep.:</th>
        <th>Packgr.:</th>
        <th></th>
      </tr>
	  <?php
	  $materialliste->index_setzen(-1);
      while (!$materialliste->listenende()) {
        $material = $materialliste->naechster_eintrag(); ?>
        <tr <?php if ($materialliste->aktueller_index()%2 != 0) { echo "id='uneven'"; } ?>>
          <td><?php echo $material->getBezeichnung(); ?></td>
          <td><?php echo $material->getMaterialgruppeBez(); ?></td>
          <td align="center"><?php echo $material->getBestand(); ?></td>
          <td align="center"><?php echo $material->getAlltagsbestand(); ?></td>
          <td align="center"><?php echo $material->getReparatur(); ?></td>
          <td align="center"><?php echo $material->getPackgroesse(); ?></td>
          <td>
            <a href="?seite=Material&action=bearbeiten&matgruppe=<?php echo $grp; ?>&index=<?php echo $materialliste->aktueller_index(); ?>"><img src="img/bearbeiten.png" width="10" height="10" border="0"/></a>
            <a href="?seite=Material&action=loeschen&matgruppe=<?php echo $grp; ?>&index=<?php echo $materialliste->aktueller_index(); ?>"><img src="img/loeschen.png" width="10" height="10" border="0"/></a>
          </td>
        </tr>
      <?php } ?>
    </table>
    <? 
  }
}
?>