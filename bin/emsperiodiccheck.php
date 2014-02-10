#!/usr/bin/php
<?php
$incdir = "/home/htdocs/moosy.net/inc/";
$to = "email@adress.de";

require($incdir."emsgetinfo.inc");
require($incdir."emsscdesc.inc");

$wa = getMaintenanceInfo("mtactive");
sleep(1);
$d2 = getEmsLiveData();
$msg = "";
$sc = $d2["Servicecode"];
$fc = $d2["Fehlercode"];
$desc = $scdesc[$d2["Servicecode"].$d2["Fehlercode"]];
$sub = "Hinweis der Heizungsanlage";

$header = 'From: Heizungsueberwachung<noreply@moosy.net>' . "\n";

if (strpos(" ".$desc,"Störungscode")){
  $sub = "STOERUNG der Heizungsanlage";
  $msg.=("STÖRUNG:\n$desc\n$sc $fc\n");
}

if (($msg=="")&&(trim($wa)=="")) die("");

if (trim($wa)!="nein"){
  $msg.=("WARTUNG:\n$wa\n");
}

if (!$msg) $msg="Die Heizungsanlage arbeitet wieder normal.";

$old=file("/tmp/lastemserrmsg");
if (trim(implode("",$old)) != trim($msg)){

  $out=fopen("/tmp/lastemserrmsg","w");
  fwrite($out,$msg);
  fclose($out);
  
  mail($to,$sub,$msg,$header);
}    
 