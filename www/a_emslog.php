<h1>
Protokoll Servicecodes
<hr></h1>
<form method="post">
<table border=0 cellpadding=3 cellspacing=3 bgcolor=#d3d0c9>
<tr>
<td></td>
<td><b>Datum</b></td>
<td><b>Zeit</b></td>
<td><b>Service-<br>Code</b></td>
<td><b>Fehler-<br>Code</b></td>
<td><b>Beschreibung</b></td>
</tr>
<?php
require("/emsincludes/emsqry.inc");
$in = getEmsStatusCodes();
require("/emsincludes/emsscdesc.inc");
foreach ($in as $l){

  $cfld  = explode("|",$l);
  $cdate = $cfld[0];
  $ctime = $cfld[1];
  $sc= $cfld[2];
  $fc= $cfld[3];
  $desc= $scdesc[$cfld[2].$cfld[3]];
  
  $col="#bbbbbb";
  $stoer = false;
  $heiz = false;
  $sleep = false;
  $tty="";




  if (substr($sc,1,1)=="H"){
    $col="#ddaaaa";
    $heiz = true;
  }   




  if (($sc=="0H")||(($sc=="0Y")&&($fc=="204"))){
    $col="#aaaadd";
    $heiz = false;
    $sleep = true;
  }   

  if ((($sc=="5H")&&($fc=="268"))){
    $col="#5555ff";
    $heiz = false;
    $sleep = false;
  }   

  if (($sc=="-A")){
    $col="#5555ff";
    $heiz = true;
    $sleep = false;
  }   


  if (strpos(" ".$desc,"Störungscode")){
    $col="#FF0000";
    $stoer = true;
  }   
  print("<tr bgcolor=".$col.">");
  if($stoer){
    print('<td bgcolor=#d3d0c9><img src="img/erroricon.png"></td>');
  } else {

    if ($heiz){
      $ls='<td bgcolor=#d3d0c9><img src="img/flameicon.png"></td>';
      print($ls);
    } else if ($sleep){
    
      $ls='<td bgcolor=#d3d0c9><img src="img/sleep.png"></td>';
      print($ls);
    
    } else {
      $ls='<td bgcolor=#d3d0c9><img src="img/info.png"></td>';
      print($ls);
    
    }
    
    
  }
  print("<td> ".$cdate.
        " </td><td> ".$ctime.
        " </td><td> ".$sc.
        " </td><td> ".$fc.
        " </td><td> ".$desc.
        " </td></tr>\n");
}
?>

</table>
<p>
<input type=submit value="Aktualisieren">
</form>