<?php
require("menu.inc");
?>
<div class="three-d">
<div class="transformed" id="topmenu">
<p>&nbsp;<p>
<table cellspacing=5 cellpadding=4 border=0>
<?php
foreach($menus as $m){
  menu($m[0],$m[1],$m[2],$m[3],$m[4]);
}
?>
</table>
</div>
</div>
<p>
