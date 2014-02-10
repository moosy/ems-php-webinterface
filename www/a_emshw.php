<h1>Heizung Letzte 3 Tage<hr></h1>

<?php
include("a_emsmenu.inc");

include 'sensor_utils.php.inc';
include 'utils.php.inc';

set_loc_settings();

$aussentemp = get_min_max_interval(SensorAussenTemp, "3 day");
$raumtemp = get_min_max_interval(SensorRaumIstTemp, "3 day");
?>

  <table style="width:90%; text-align:left;">
    <tr height=10></tr>
    <tr><td>
      <table border=0 cellspacing=0 cellpadding=0 width="100%">
        <tr><td>
          <?php print_min_max_table("Außentemperatur", $aussentemp); ?>
        </td></tr>
        <tr height=6></tr>
        <tr><td>
          <?php print_min_max_table("Raumtemperatur", $raumtemp); ?>
        </td></tr>
      </table>
    </td></tr>
  </table>
  <h3>Graphen</h3>
  <p>
    <img src="graphs/aussentemp-halfweek.png" width=90% alt="Außentemperaturentwicklung">
  </p>
  <p>
    <img src="graphs/raumtemp-halfweek.png" width=90% alt="Raumtemperaturentwicklung">
  </p>
  <p>
    <img src="graphs/kessel-halfweek.png" width=90% alt="Kesseltemperaturentwicklung">
  </p>
  <p>
    <img src="graphs/ww-halfweek.png" width=90% alt="Warmwassertemperaturentwicklung">
  </p>
  <p>
      <img src="graphs/brenner-halfweek.png" width=90% alt="Brennerstatus">
  </p>
        
  <p>
      <img src="graphs/pumpen-halfweek.png" width=90% alt="Pumpenstatus">
  </p>
        