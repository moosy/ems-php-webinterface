<html>
<head>
<META http-equiv="Expires" CONTENT="0">
<meta http-equiv="refresh" content="2; URL=?seite=a_emsdet.php&menu=no">
<link href="/moosy.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0"
rightmargin="0" marginwidth="0" marginheight="0">
<?php
require ("emsqry.inc");
$data = getEmsLiveData();
$d = array();
foreach ($data as $k => $v){
  $d[$k]=htmlentities($v);
}
print("<h3>Details</h3><table bgcolor=#dddddd>");
print("<table bgcolor=#cccccc><tr><td valign=top>");
print("<table bgcolor=#eeeeee>");
$i = 0;
foreach ($d as $k => $v){
  $i++;
  if ($i > count($d)/3){
    print("</table></td><td>&nbsp;</td><td valign=top><table bgcolor=#eeeeee>");
    $i=0;
  }
  print("<tr><td><font size=-4>$k</font></td><td><font size=-4>$v</font></td></tr>");
}
?>
</table>
</td></tr></table>
</body>
</html>