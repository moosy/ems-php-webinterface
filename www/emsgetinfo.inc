<?php
require_once("emsqry.inc");
$wwcache = array();
$hkcache = array();
if (!open_ems()) die("<h3>FATAL: Keine Verbindung zum EMS-Bus möglich.</h3>");

function cond($type){
  switch($type){
  case("aussentemp"):
    return (getHKInfo("refinput")=="Aussen");
    break;
  case("abschalt"):
    return (getHKInfo("redmode")=="Abschalt");
    break;
  case("frostaussen"):
    return (getHKInfo("frostmode")=="Aussen");
    break;
  case("aussenhalt"):
    return (getHKInfo("redmode")=="Aussenhalt");
    break;
  case("desinfect"):
    return (getWWInfo("tdstat")=="on");
    break;
  case("wwactive"):
    return (getWWInfo("wwactive")=="on");
    break;
  case("heizactive"):
    return (getUBAInfo("heizactive")=="on");
    break;
  case("daempfung"):
    return (getHKInfo("daempfung")=="on");
    break;

  }


}


function getWWInfo($src){
  global $wwcache;
  $srch = array("ww" => "1DATA: Betriebsart Warmwasser =",
                "zir" => "1DATA: Betriebsart Zirkulationspumpe =",
                "spzir" => "3DATA: Anzahl Schaltpunkte Zirkulation =",
                "wwtag" => "3DATA: Warmwassertemperatur Tag =",
                "wwactive" => "3DATA: Warmwasser am Kessel aktiviert =",
                "limittemp" => "1DATA: max. Warmwassertemperatur =",
                "loadled"   => "1DATA: Einmalladungstaste =",
                "tdstat" => "1DATA: Thermische Desinfektion =",
                "tdday" => "1DATA: Therm. Desinfektion Tag (7:alle) =",
                "tdhour" => "1DATA: Therm. Desinfektion Stunde =",
                "tdtemp" => "3DATA: Solltemperatur Thermische Desinfektion =");

  $p2 = substr($srch[$src],0,1);
  $searchstr = substr($srch[$src],1);
  $part=($p2=="1"?"":$p2);

  if (isset($wwcache[$p2])) {
    $res = $wwcache[$p2];
#    print("#############HIT $src ###########");
  } else {
#    usleep(300000);
#    print("===========MISS $src ============");

    $res=doEmsCommand("ww getstatus".$part);
    $wwcache[$p2] = $res;
  }

  foreach($res as $r){
    $r2 = str_replace($searchstr,"",$r);
    if ($r2 != $r) {
      $r2 = trim($r2);
      switch ($src){
      case "spzir":
        $r2 = substr($r2,0,1);
        if ($r2=="s") $r2="7";
        break;
      case "tdstat":
        if ($r2=="ein"){ $r2="on";} else{ $r2="off";};
        break;
      case "tdtemp":
      case "tdhour":
        $r2 = trim(substr($r2,0,2));
        break;
      case "ww":
      case "zir":
        if ($r2=="Automatik") $r2="auto";
        if ($r2=="staendig an") $r2="on";
        if ($r2=="staendig aus") $r2="off";
        break;
      case "loadled":
      case "wwactive":
        if ($r2 == "AN") $r2="on";
        if ($r2 == "AUS") $r2="off";
        break;

      }
#      print("$src -- $r2");
        
      return trim($r2);
    }
  }
}

function getHolVacInfo($src){
  $res = doEmsCommand("hk1 get".$src);  
  $out = array();
  foreach($res as $l){
    if (substr($l,0,5)=="BEGIN") $out["von"] = str_replace("-",".",substr($l,6,10));
    if (substr($l,0,3)=="END") $out["bis"] = str_replace("-",".",substr($l,4,10));
  }
  return $out;  

}
function getContactInfo($src){
  $res = doEmsCommand("getcontactinfo ".$src);  
  $out = array();
  foreach($res as $l){
    if (($l2=str_replace("DATA: Kontaktinfo = ","",$l))!=$l) return (trim($l2));;
  }
  return "";  
}

function getHKInfo($src){
  global $hkcache;
  $srch = array("tag" => "2DATA: Raumtemperatur Tag =",
                "nacht" => "2DATA: Raumtemperatur Nacht =",
                "tagbetr" => "1DATA: HK-Tagbetrieb =",
                "frost" => "2DATA: Frostschutztemperatur =",
                "temptemp" => "3DATA: Temporaere Raumtemperatur (0:inaktiv) =",
                "nachtred" => "3DATA: Aussentemp. fuer Absenkbetrieb =",
                "absquit"  => "3DATA: Absenkung abbrechen ab =",
                "ferient"  => "2DATA: Raumtemperatur Ferien =",
                "summertime" => "2DATA: Sommerbetrieb ab =",
                "auslegtemp" => "3DATA: Auslegungstemperatur =",
                "minaussentemp" => "4DATA: Minimale Aussentemperatur =",
                "gebaeude" => "4DATA: Gebaeudeart =",
                "daempfung" => "4DATA: Daempfung Aussentemperatur =",
                "minvorlauf" => "2DATA: Minimale Vorlauftemperatur =",
                "maxvorlauf" => "3DATA: Maximale Vorlauftemperatur =",
                "redmode" => "3DATA: Betriebsart =",
                "refinput" => "3DATA: Fuehrungsgroesse =",
                "refinputvac" => "3DATA: Absenkung Urlaub =",
                "maxroomeffect" => "2DATA: Max. Raumtemperatureinfluss =",
                "schedoptimizer" => "2DATA: Optimierung Schaltzeiten =",
                "frostmode" => "3DATA: Frostschutzart =",
                "raumoffset" => "2DATA: Raumtemperaturoffset =");
                


  $p2 = substr($srch[$src],0,1);
  $searchstr = substr($srch[$src],1);
  $part=($p2=="1"?"":$p2);


  if (isset($hkcache[$p2])) {
    $res = $hkcache[$p2];
#    print("#############HIT $src ###########");
  } else {
#    print("===========MISS $src ============");
#    usleep(200000);
    $res=doEmsCommand("hk1 getstatus".$part);
    $hkcache[$p2] = $res;
  }

  foreach($res as $r){
    $r2 = trim(str_replace($searchstr,"",$r));
    if ($r2 != $r) {
    switch($src){
      case "refinput":
        if ($r2 == "Aussentemperaturgefuehrt") $r2="Aussen";
	if ($r2 == "Raumtemperaturgefuehrt") $r2="Raum";
        break;
      case "refinputvac":
        if ($r2 == "Aussenhaltebetrieb") $r2 = "Aussen";
        if ($r2 == "Raumhaltebetrieb") $r2 = "Raum";
        break;
      case "schedoptimizer":
      case "tagbetr":
        if ($r2 == "AN") $r2="on";
        if ($r2 == "AUS") $r2="off";
        break;
      case "frostmode":
        if ($r2 == "kein") $r2="off";
        if ($r2 == "per Aussentemperatur") $r2="Aussen";
        if ($r2 == "per Raumtemperatur") $rr2="Raum";
        break;
      case "redmode":
        if ($r2 == "Aussenhaltebetrieb") $r2 = "Aussenhalt";
        if ($r2 == "Raumhaltebetrieb") $r2 = "Raumhalt";
        if ($r2 == "Abschaltbetrieb") $r2 = "Abschalt";
        if ($r2 == "Reduzierter Betrieb") $r2 = "Reduziert";
        break;
      case "gebaeude":
         break;
      case "daempfung":
        if ($r2 == "aktiviert") $r2="on";
        if ($r2 == "deaktiviert") $r2="off";
        break;
      default:
        $r2 = trim($r2);
        $r2 = substr($r2,0,-3);
      }
#      print("$src -- $r2");
      return $r2;
    }
  }
}

function getUBAInfo($src){
  global $ubacache;
  $srch = array("antipen" => "DATA: Antipendelzeit =",
                "heizactive" => "DATA: Heizfunktion am Kessel aktiviert =",
                "kpmin" => "DATA: min. Kesselpumpenleistung =",
                "kpmax"  => "DATA: max. Kesselpumpenleistung =",
                "kpnachl"  => "DATA: Kesselpumpennachlauf =",
                "hystein"  => "DATA: Einschalthysterese =",
                "hystaus"  => "DATA: Abschalthysterese =");


  if (isset($ubacache)) {
      $res = $ubacache;

  } else {
#      usleep(200000);
      $res=doEmsCommand("uba getstatus");
      $ubacache = $res;
  }
                      


  foreach($res as $r){
    $r2 = str_replace($srch[$src],"",$r);
    if ($r2 != $r) {
      $r2 = trim($r2);
      switch ($src){
      case "heizactive":
        if ($r2 == "AN") $r2="on";
        if ($r2 == "AUS") $r2="off";
        break;

      default:
        $r2=trim(substr($r2,0,3));
        if (substr($r2,1,1)==" ")  $r2=trim(substr($r2,0,2));
      }
      return $r2;
    }
  }
}

function getMaintenanceInfo($src){
  global $maintcache;
  $srch = array("mtmode" => "DATA: Wartungsmeldungen = ",
                "mthours" => "DATA: Wartungsintervall Betriebsstunden =",
                "mtday"  => "DATA: Wartungsintervall Tag =",
                "mtmonth" => "DATA: Wartungsintervall Monat =",
                "mtactive"  => "DATA: Wartung faellig =",
                "mtyear"  => "DATA: Wartungsintervall Jahr =");

 
 if ($src == "mtactive"){
   $res=doEmsCommand("uba isinmaintenance");

 } else {
   if (isset($maintcache)) {
        $res = $maintcache;

    } else {
#        usleep(200000);
        $res=doEmsCommand("uba getmaintenance");
        $maintcache = $res;
    }
  }                      
  foreach($res as $r){
    $r2 = str_replace($srch[$src],"",$r);
    if ($r2 != $r) {
      $r2 = trim($r2);
      if ($r2 == "nach Datum") $r2 = "bydate";
      if ($r2 == "nach Betriebsstunden") $r2 = "byhours";
      if ($r2 == "keine") $r2 = "off";
      $r2 = str_replace(" *100h","",$r2);
      return $r2;
    }
  }
}

function getPartyInfo($src){
  global $ppcache;
  $srch = array("pause" => "DATA: Verbleibende Stunden Pausenmodus =",
                "party" => "DATA: Verbleibende Stunden Partymodus =");

  if (isset($ppcache)){
    $res = $ppcache;
  } else {
    $res=doEmsCommand("hk1 getpartypause");
    $ppcache=$res;
  }

  foreach($res as $r){
    $r2 = str_replace($srch[$src],"",$r);
    if ($r2 != $r) {
      $r2 = trim($r2);
      $r2=trim(substr($r2,0,2));
      return $r2;
    }
  }
}

?>