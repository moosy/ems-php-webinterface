<h1>
Schaltprogramme
<hr>
</h1>&nbsp;
<table id=prog width=250px bgcolor=#cccccc cellspacing=0 cellpadding=5>
<tr bgcolor=#ffffff height=50px><td colspan=8></td></tr>
<tr height=30px><td colspan=8 bgcolor=#cccccc><div id=prc align=center></div></td></tr>
<tr height=3px>
<td id=p0 bgcolor=#eeeeee></td>
<td id=p1 bgcolor=#dddddd></td>
<td id=p2 bgcolor=#cccccc></td>
<td id=p3 bgcolor=#bbbbbb></td>
<td id=p4 bgcolor=#aaaaaa></td>
<td id=p5 bgcolor=#999999></td>
<td id=p6 bgcolor=#888888></td>
<td id=p7 bgcolor=#777777></td>
</tr>
</tablel>
<script type="text/javascript">
function progress(perc){
    document.getElementById("prc").innerHTML="<b>Bitte warten...</b> ("+perc+"%)";
  for (i=0; i<=perc/12; i++){
    if (i<8) document.getElementById("p"+i).style.backgroundColor="#8888ff";
  }
}
progress(10);
</script>

<?php
flush();
include("/emsincludes/emsqry.inc");
$changed = false;
$currsrc = "hk1-1";
$ca = explode("-",$currsrc);
$currtgt = $ca[0];
$currsub = $ca[1];

$sch = false;

if (isset($_POST["emstgt"])){

  $currsrc = trim($_POST["emstgt"]);
  $ca = explode("-",$currsrc);
  $currtgt = $ca[0];
  $currsub = $ca[1];
  
  switch (trim($_POST["emstrans"])){
    case "Lesen":
      $sch = readSchedule($currtgt,$currsub);
      break;
    case "Schreiben":
      $sch = html2sched($_POST,"schedule");
      programSchedule($sch,$currtgt,$currsub);
      $sch = readSchedule($currtgt,$currsub);
      break;
    case "Aktualisieren":
      $sch = html2sched($_POST,"schedule");
      $changed = true;
      break;
  
  }
}
print('<script type="text/javascript">progress(90);</script>');
flush_buffers();
if ($sch===false){
   $sch = readSchedule($currtgt,$currsub);
}
print('<script type="text/javascript">progress(95);</script>');
flush_buffers();
if (isset($_POST["setmode"])){
  
  doEmsCommand("hk1 selectschedule ".trim($_POST["hk1"]));
  doEmsCommand("ww selectschedule ".trim($_POST["ww"]));
  doEmsCommand("ww zirkpump selectschedule ".trim($_POST["zir"]));

}




$emstgt= array("Heizkreis Eig.1" => "hk1-1",
               "Heizkreis Eig.2" => "hk1-2",
               "Warmwasser" => "ww-",
               "Zirkulation" =>  "wwzirkpump-");


$emspathk = array("Familie"=>"family",
                  "Morgen"=>"morning",
                  "Früh"=> "early",
                  "Abend"=> "evening",
                  "Vormittag"=> "forenoon",
                  "Nachmittag"=> "afternoon",
                  "Mittag"=>"noon",
                  "Single"=>"single",
                  "Senioren"=>"senior",
                  "Eigenes Prg. 1"=>"custom1",
                  "Eigenes Prg. 2"=>"custom2");

$emspatww = array("Eigenes Prog." => "custom",
                  "wie Heizkreis" => "hk");

$emspatzi = array("Eigenes Prog."  => "custom",
                  "wie Warmw." => "hk");
                  
                  
?>
<?php
print("<form method=post>");
schedule2graph($sch,"schedule.png");

print("<table id=inhalt cellpadding=7 cellspacing=3 style='display: none;'><tr><td rowspan=2 width=100%>");
print("<img src=graphs/schedule.png?t=".time()." width=100%><p>");
print("</td><td valign=top bgcolor=#cccccc width=200px>");


print("<h3>RC35-Speicher</h3>");
print("<select name=emstgt>");
foreach ($emstgt as $e => $ei){
  print("<option value=$ei ".($currsrc==$ei?"selected":"")." >$e</option>");
}
print("</select><br>");
print("<input type=submit name=emstrans value=Lesen><br>");
print("<input type=submit name=emstrans value=Schreiben><br>");
print("<input type=submit name=emstrans value=Aktualisieren><br>");


print("</td></tr><tr><td valign=top bgcolor=#cccccc>");



print("<h3>Programme</h3>");

$act = getActSchedule("hk1");
print("<b>Heizkreis</b><br>");
print("<select name=hk1>");
foreach ($emspathk as $e => $ei){
  print("<option value=$ei ".($act==$ei?"selected":"").">$e</option>");
}
print("</select><br>");

$act = getActSchedule("ww");
print("<b>Warmwasser</b><br>");
print("<select name=ww>");
foreach ($emspatww as $e => $ei){
  print("<option value=$ei ".($act==$ei?"selected":"").">$e</option>");
}
print("</select><br>");

$act = getActSchedule("zir");
print("<b>Zirkulation</b><br>");
print("<select name=zir>");
foreach ($emspatzi as $e => $ei){
  print("<option value=$ei ".($act==$ei?"selected":"").">$e</option>");
}
print("</select><br>");

print("<p><input type=submit name=setmode value=Setzen>");

print("</td></tr></table>");

$desc = end(array_keys($emstgt,$currsrc));
if ($changed) $desc .= " (Geändert)";
print("<table cellpadding=10px>");

print("<tr><td bgcolor=#bbbbbb colspan=7><h3>$desc</h3></td></tr>");

schedule2html($sch ,"schedule");


?>

</table>
</form>

<?php
close_ems();
?>
<script type="text/javascript">
  document.getElementById("prog").style.display="none";
  document.getElementById("inhalt").style.display="block";
</script>