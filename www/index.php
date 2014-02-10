<?php
header('Content-Type: text/html; charset=iso-8859-1');
$seite=$_GET["seite"];
$seite = str_replace("/","",$seite);
$seite = substr($seite,0,-4);
$seite = str_replace(".","",$seite);
$seite = trim(substr($seite,0,10));
if ($seite == "") $seite="main";
$seite .= ".php";
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta name="author" content="Michael Moosbauer">
  <META http-equiv="Expires" CONTENT="0">
  <link rel="stylesheet" href="moosy.css" type="text/css">
  <title>Buderus EMS-Bus Interface</title>
</head>
<body>
<?php
$menu = ($_GET["menu"]!="no");
if ($menu) {
  require("top.php");
  print('<div id="content"><p>&nbsp;<p>');
} else {
  print('<div id="nomenu">');
}  
print("\n");
if (file_exists($seite)){
  require($seite);
} else {
  require("notfound.php");
}
print("\n</div>\n\n");
?>
</body>
</html>
