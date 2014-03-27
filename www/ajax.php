<?php
header('Content-Type: text/html; charset=iso-8859-1');
$seite=$_GET["seite"];
$seite = str_replace("/","",$seite);
$seite = substr($seite,0,-5);
$seite = str_replace(".","",$seite);
$seite = trim(substr($seite,0,10));
if ($seite == "") $seite = "--doesnotexist--";
$seite .= ".ajax";
if (!file_exists($seite)){
  header("HTTP/1.0 404 Not Found");
  print("404 NOTFOUND");
} else {
  require($seite);
}
