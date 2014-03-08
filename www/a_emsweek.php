<h1>Heizung Letzte Woche<hr></h1>

<?php
include("a_emsmenu.inc");

include 'sensor_utils.php.inc';
include 'utils.php.inc';

set_loc_settings();

$aussentemp = get_min_max_interval(SensorAussenTemp, "1 week");
$raumtemp = get_min_max_interval(SensorRaumIstTemp, "1 week");
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
    <img src="graphs/aussentemp-week.png" width=90% alt="Außentemperaturentwicklung">
  </p>
  <p>
    <img src="graphs/raumtemp-week.png" width=90% alt="Raumtemperaturentwicklung">
  </p>
  <p>
    <img src="graphs/kessel-week.png" width=90% alt="Kesseltemperaturentwicklung">
  </p>
  <p>
    <img src="graphs/ww-week.png" width=90% alt="Warmwassertemperaturentwicklung">
  </p>
  <p>
    <img src="graphs/brenner-week.png" width=90% alt="Brennerstatus">
  </p>
  <p>
      <img src="graphs/pumpen-week.png" width=90% alt="Pumpenstatus">
        </p>
                