<?php
require_once("emsqry.inc");
if (!open_ems()) die("<h3>FATAL: Keine Verbindung zum EMS-Bus möglich.</h3>");


$sets = array("nachtred" => "hk1 nightdoredtemp",
              "frost"    => "hk1 frosttemp",
              "summertime"  => "hk1 summertimetemp",
              "absquit"  => "hk1 stopnighttemp",
              "ferient"  => "hk1 holidaytemperature",
              "minvorlauf" => "hk1 minheatflowtemp",
              "maxvorlauf" => "hk1 maxheatflowtemp",
              "maxroomeffect"    => "hk1 maxroomeffect",
              "schedoptimizer"   => "hk1 schedoptimizer",
              "redmode"    => "hk1 redmode",
              "refinput"   => "hk1 refinput",
              "refinputvac" => "hk1 refinputvac",
              "frostmode" => "hk1 frostmode",
              "limittemp" => "ww limittemp",
              "loadled"   => "ww loadled",
              "spzir"    => "ww zirkpump count",
              "zirmode"  => "ww zirkpump mode",
              "wwmode"   => "ww mode",
              "tdstat"   => "ww thermdesinfect mode",
              "tdtemp"   => "ww thermdesinfect temperature",
              "tdday"    => "ww thermdesinfect day",
              "tdhour"    => "ww thermdesinfect hour",
              "antipen"  => "uba antipendel",
              "contact"  => "setcontactinfo",
              "kpnachl"  => "uba pumpdelay",
              "kp"       => "uba pumpmodulation",
              "maint"    => "uba setmaintenance",
              "urlaub"   => "hk1 vacationmode",
              "ferien"   => "hk1 holidaymode",
              "hystein"  => "uba hyst on",
              "hystaus"  => "uba hyst off",
              "ww"       => "ww temperature",
              "temptemp" => "hk1 temptemp",
              "temptempoff" => "hk1 temptemp 0 ",
              "day"      => "hk1 daytemperature",
              "night"    => "hk1 nighttemperature",
              "at"       => "hk1 designtemp",
              "gebaeude" => "hk1 building",
              "daempfung" => "hk1 enabledamping",
              "maut"     => "hk1 minouttemp",
              "ro"       => "hk1 tempoffset",
              "party"    => "hk1 partymode",
              "pause"    => "hk1 pausemode");
              

foreach ($sets as $k => $v){
  if (isset($_POST["set".$k])){
      $nv = trim($_POST[$k]);
      switch($k){ // Sonderbehandlungen
        case "hystein":
          $nv = 256 + $nv;
          break;
        case "kp":
          $nv = trim($_POST["kpmin"])." ".trim($_POST["kpmax"]);
          break;      
          
        case "urlaub":
        case "ferien":
        
          $nv = trim($_POST[$k."_von"])." ".trim($_POST[$k."_bis"]);
          $nv = str_replace(".","-",$nv);
          break;  

        case "maint":
          $mm = trim($_POST[mtmode]);
          $nv = "off";
          if ($mm == "bydate") $nv = "bydate ".str_replace(".","-",$_POST["mtdate_von"]);
          if ($mm == "byhours") $nv = "byhours ".($_POST["mthours"]/100);
          break;

        case "contact":
          doEmsCommand($v." 1 ".trim($_POST["contact1"]));
          sleep(1);
          $nv = " 2 ".trim($_POST["contact2"]);
          break;

      }
#      print($v." ".$nv);
      doEmsCommand($v." ".$nv);
  }
}

if (isset($_POST["hkmode"])){
  switch($_POST["hkmode"]){
  case "Tag":
    doEmsCommand("hk1 mode day");
    break;
  case "Nacht":
    doEmsCommand("hk1 mode night");
    break;
  case "Auto":
    doEmsCommand("hk1 mode auto");
    break;
  }
  sleep(1);
  doEmsCommand("hk1 getstatus");
  sleep(1);

}

if (isset($_POST["wwload"])){
  switch($_POST["wwload"]){
  case "Starten":
    doEmsCommand("ww loadonce");
    break;
  case "Abbrechen":
    doEmsCommand("ww canloadonce");
    break;
  }
}

