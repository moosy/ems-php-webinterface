<?php
function notavail(){
  print("<td colspan=2 bgcolor=#777777><font size=-2 color=#cccccc><center>verfügbar nach 'Setzen'</center></font></td>");
}

function tempchooser($name,$min,$max,$step=1,$unit="°C",$empty="wählen",$sel=0){
  print("<select  name=$name id=$name onchange='w_act();'><option value=0>$empty</option>\n");
  for($i=$min;$i<=$max;$i+=$step){
    print("<option value='$i' ".($i==$sel?"selected":"").">$i $unit</option>\n");
  }
  print("</select>");
}

function hourchooser($name,$sel=0){
  print("<select  name=$name id=$name onchange='w_act();'>\n");
  for($i=0;$i<=23;$i++){
    $hs = substr("00".$i,-2).":00";
    print("<option value='$i' ".($i==$sel?"selected":"").">$hs</option>\n");
  }
  print("</select>");
}

function datechooser($name,$defv="TT.MM.JJJJ",$defb=""){
  print("<input type=text size=10 name=".$name."_von value=$defv >");
  if ($defb) print(" -  <input type=text size=10 name=".$name."_bis value=$defb >\n");
}

function modechooser($name,$sel){
  print("<select  name=$name id=$name onchange='w_act();' id=$name>");
  print("<option value=on ".($sel=="on"?"selected":"")." >ständig an</option>");
  print("<option value=off ".($sel=="off"?"selected":"")." >ständig aus</option>");
  print("<option value=auto ".($sel=="auto"?"selected":"")." >Automatik</option>");
  print("</select>");
}

function maintchooser($name,$sel){
  print("<select  name=$name id=$name onchange='w_act();' id=$name onchange='w_act();'>");
  print("<option value=off ".($sel=="off"?"selected":"")." >keine</option>");
  print("<option value=bydate ".($sel=="bydate"?"selected":"")." >nach Datum</option>");
  print("<option value=byhours ".($sel=="byhours"?"selected":"")." >nach Betriebsstunden</option>");
  print("</select>");
}

function onoffchooser($name,$sel){
  print("<select  name=$name id=$name onchange='w_act();'>");
  print("<option value=on ".($sel=="on"?"selected":"")." >Aktiviert</option>");
  print("<option value=off ".($sel=="off"?"selected":"")." >Deaktiviert</option>");
  print("</select>");
}

function redmodechooser($name,$sel){
  print("<select  name=$name id=$name onchange='w_act();'>");
  print("<option value=Abschalt ".($sel=="Abschalt"?"selected":"")." >Abschaltbetrieb</option>");
  print("<option value=Reduziert ".($sel=="Reduziert"?"selected":"")." >Reduzierter Betrieb</option>");
  print("<option value=Raumhalt ".($sel=="Raumhalt"?"selected":"")." >Raumhaltbetrieb</option>");
  print("<option value=Aussenhalt ".($sel=="Aussenhalt"?"selected":"")." >Aussenhaltbetrieb</option>");
  print("</select>");
}

function refinputchooser($name,$sel){
  print("<select  name=$name id=$name onchange='w_act();'>");
  print("<option value=Raum ".($sel=="Raum"?"selected":"")." >Raumtemperatur</option>");
  print("<option value=Aussen ".($sel=="Aussen"?"selected":"")." >Aussentemperatur</option>");
  print("</select>");
}

function refinputvacchooser($name,$sel){
  print("<select  name=$name id=$name onchange='w_act();'>");
  print("<option value=Raum ".($sel=="Raum"?"selected":"")." >Raumhaltbetrieb</option>");
  print("<option value=Aussen ".($sel=="Aussen"?"selected":"")." >Aussenhaltbetrieb</option>");
  print("</select>");
}


function frostmodechooser($name,$sel){
  print("<select  name=$name id=$name onchange='w_act();'>");
  print("<option value=off ".($sel=="off"?"selected":"")." >kein</option>");
  print("<option value=Raum ".($sel=="Raum"?"selected":"")." >per Raumtemperatur 5°C</option>");
  print("<option value=Aussen ".($sel=="Aussen"?"selected":"")." >per Aussentemperatur</option>");
  print("</select>");
}

function gebaeudechooser($name,$sel){
  print("<select  name=$name id=$name onchange='w_act();'>");
  print("<option value=leicht ".($sel=="leicht"?"selected":"")." >leicht</option>");
  print("<option value=mittel ".($sel=="mittel"?"selected":"")." >mittel</option>");
  print("<option value=schwer ".($sel=="schwer"?"selected":"")." >schwer</option>");
  print("</select>");
}


function daychooser($name,$sel){
  global $daystr;
  $ds2 = $daystr;
  $ds2[7] = "täglich";
  print("<select  name=$name id=$name onchange='w_act();'>");
  foreach ($ds2 as $k => $v){
    print("<option value=$k ".($sel==$k?"selected":"")." >$v</option>");
  }
  print("</select>");
}

function zirkchooser($name,$sel){
  $op = array("1" => "1x 3min",
              "2" => "2x 3min",  
              "3" => "3x 3min",
              "4" => "4x 3min",
              "5" => "5x 3min",
              "6" => "6x 3min",
              "7" => "ständig");
              
  print("<select  name=$name id=$name onchange='w_act();'>");
  foreach ($op as $k => $v){
    print("<option value=$k ".($sel==$k?"selected":"")." >$v</option>");
  }
  print("</select>");
}
?>