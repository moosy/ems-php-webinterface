#!/usr/bin/php
<?php
$incdir = "/home/htdocs/moosy.net/inc/";
require($incdir."emsgetinfo.inc");

$AT = getHKInfo("auslegtemp");
$RTO = getHKInfo("raumoffset");
$TT = getHKInfo("tag");
$TN = getHKInfo("nacht");
$MAUT = getHKInfo("minaussentemp")+0;

$data = getEmsLiveData();

$GAP = substr($data["Gedämpfte Außentemperatur"],0,-3);


$ATT= $AT + $RTO + $TT - 19;
$BTT= $RTO + $TT + 1;

$ATN= $AT + $RTO + $TN - 21;
$BTN= $RTO + $TN - 1;

$f1 = fopen("/tmp/hkt.dat","w");
fwrite($f1,"$MAUT $ATT\n");
fwrite($f1,"20  $BTT\n");
fclose($f1);

$f1 = fopen("/tmp/hkn.dat","w");
fwrite($f1,"$MAUT $ATN\n");
fwrite($f1,"20  $BTN\n");
fclose($f1);


$f1 = fopen("/tmp/hka.dat","w");
fwrite($f1,"$GAP 5\n");
fwrite($f1,"$GAP 45\n");
fclose($f1);


if (cond("abschalt")) unlink("/tmp/hkn.dat");

$f1 = fopen("/tmp/temp.dat","w");
fwrite($f1,"$AT\n$RTO\n$TT\n$TN\n$MAUT\n");
fclose($f1);

exec("/usr/local/bin/ems-heizkurve.py");

 