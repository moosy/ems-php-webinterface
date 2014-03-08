<h1>
Fehlerspeicher
<hr></h1>
<form method="post">

<?php

function beginTable(){
?>
<table border=0 cellpadding=3 cellspacing=3 bgcolor=#d3d0c9>
<tr>
<td></td>
<td><b>Datum</b></td>
<td><b>Zeit</b></td>
<td><b>Dauer</b></td>
<td><b>Service-<br>Code</b></td>
<td><b>Fehler-<br>Code</b></td>
<td><b>Beschreibung</b></td>
</tr>

<?php
}

require("/emsincludes/emsqry.inc");

$in = array("Anlagenfehler" => doEmsCommand("rc geterrors"),
            "Kesselfehler" => doEmsCommand("uba geterrors"));
            
require("/emsincludes/emsscdesc.inc");

foreach ($in as $edesc => $data){
  print("<h2>$edesc</h2>");
  beginTable();

  $haderrors = false;
  foreach($data as $l){


  $cfld  = explode(" ",$l);
  if (count($cfld) != 7) continue;
  $haderrors = true;
  $kind = substr($cfld[0],0,1);
  $block = ($kind == "B");
  $lock = ($kind == "L");
  
  $cdate = str_replace("-",".",$cfld[1]);
  $ctime = $cfld[2];
  $sc= $cfld[4];
  if ($edesc == "Anlagenfehler") $sc = "A".$sc;
  $fc= $cfld[5];
  $dur = $cfld[6];
  $desc= $scdesc[$cfld[4].$cfld[5]];
  
  $col="#bbbbbb";

  print("<tr bgcolor=".$col.">");
  if($lock){
    print('<td bgcolor=#d3d0c9><img src="img/error_lock.png"></td>');
  } else if ($block){
    $ls='<td bgcolor=#d3d0c9><img src="img/error_block.png"></td>';
    print($ls);
  } else {
    $ls='<td bgcolor=#d3d0c9><img src="img/info.png"></td>';
    print($ls);
    
  }
    
    
  print("<td> ".$cdate.
        " </td><td> ".$ctime.
        " </td><td> ".$dur.
        " </td><td> ".$sc.
        " </td><td> ".$fc.
        " </td><td> ".$desc.
        " </td></tr>\n");
}
if (!$haderrors)print("<tr><td></td><td colspan=6>keine Einträge</td></tr>");
?>

</table>
<p>
<?php
}
close_ems();
?>
<input type=submit value="Aktualisieren">
</form>