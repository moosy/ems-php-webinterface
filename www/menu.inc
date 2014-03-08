<?php
require("mconfig.php");
# Menüs anzeigen
     
function menu($mtitle,$murl,$mdesc,$mpages,$acclevel){
global $seite;
global $submenus;

  if (($acclevel=="public") ||
      ($acclevel == "ALL")) {

    print("<tr> <td valign=top width=80 align=left colspan=2 bgcolor="); 

    $tit1="";$tit2="";
    if  ($acclevel == "ALL") $tit1="  ";
    if (in_array($seite,$mpages) || ($seite == $murl)) {
      $tit1="<font color=#dddddd >";
      if ($seite == $murl) {
        $tit1="<font color=#ff8800>"; 
      } else {
        $tit1="<font color=#ffffff>";
      };

      $tit2="</font>";
      print("#000099");
    } else {
      print("#eeeeee");
    }

    print(">\n"); 

    print('<a href="?seite='.$murl.'"><div class=menuhead>'.$tit1.$mtitle.$tit2.'</div></a>'."\n");
    $smstr="";

    if ($tit1 != "")  {
      foreach ($mpages as $desc => $url){  
        $desc2="<font color=#000000>".$desc."</font>";
        if ($url == $seite) {
          $desc2="<font color=#ff0000>".$desc."</font>";
        }
        if (substr($desc,0,6)!="HIDDEN") $smstr.='<a href="?seite='.$url.'"><div class=menusub>'.$desc2.'</div></a>'."\n";
      }
    }
    print("</td> </tr>\n\n");

    if ($smstr != "") {
      print('<tr> <td border=0 >&nbsp;</td> <td border=0 bgcolor=#dddddd align=left>'.$smstr.'</td> </tr>'."\n");
    } 

  }
}

?>