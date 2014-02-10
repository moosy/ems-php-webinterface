<?php
$graphpath = "/home/htdocs/moosy.net/www/graphs/";
$html_cols=3;
$daystr = array("Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag","Sonntag");

function open_ems(){
  global $ems_socket;
  $address = "127.0.0.1";
  $service_port=7777;
  
  if (!isset($ems_socket)){
    $ems_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    if ($ems_socket === false) {
      die ("socket_create() fehlgeschlagen: Grund: " . socket_strerror(socket_last_error()) . "\n");
    }

    $result = socket_connect($ems_socket, $address, $service_port);
    if ($result === false) {
      die ("socket_connect() fehlgeschlagen.\nGrund: ($result) " . socket_strerror(socket_last_error($ems_socket)) . "\n");
    }
    socket_set_option($ems_socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>1, 'usec'=>0));
  
    doEmsCommand(""); # Skip greeting
    return $result;
  }
  return true;
}

function close_ems(){
  global $ems_socket;
  socket_close($ems_socket);
}


function idiv($a,$b){
  return (($a - ($a % $b)) / $b);
}

function doEmsCommand($in){
  global $ems_socket;
  $in .= "\n\n";
  $sleep=10000;
  do {
#   print("DEBUG: SEND >>> $in <<<");
    if ($sleep > 5000000){
      close_ems();
      sleep(1);
      open_ems();
 
    }

    usleep($sleep);
    socket_write($ems_socket, $in, strlen($in));
    $res = "";
    $tout = 0;
    while ($out = socket_read($ems_socket, 4)) {
        $tout++;
        if (strlen($out)==0) $tout += 100;
        if ($tout > 200) {
#          print("TIMEOUT\n");
          $res="";
          break;
        }
        $res .=$out;
        if (strpos($res,"\nERRBUSY\n")) {
          $sleep = $sleep*2;
          break;
        }

        if (strpos($res,"\nOK\n")) {
#          print("SUCCESS!\n");
          break;
        }
        if (strpos($res,"\nERRARGS\n")) break;
        if (strpos($res,"\nERRCMD\n")) break;
    }
#    var_dump($res);
  } while (substr(trim($res),-7)=="ERRBUSY");

#  print("DEBUG: RECV #># $res #<#\n");
  $resa = explode("\n",$res);
  if (!trim(end($resa))) array_pop($resa);
  return $resa;

}


function parseTime($in){
  //  Convert MO 12:34 in minutes since Monday, 0:00

  $si = explode(" ",$in);
  $day=99;
  if ($si[0] == "MO") $day=0;
  if ($si[0] == "TU") $day=1;
  if ($si[0] == "WE") $day=2;
  if ($si[0] == "TH") $day=3;
  if ($si[0] == "FR") $day=4;
  if ($si[0] == "SA") $day=5;
  if ($si[0] == "SU") $day=6;
  $sj = explode(":",$si[1]);
  if ($day==99) return false;
  return ($day*24*60 + $sj[0]*60 + $sj[1] - ($sj[1] % 10) ); // 10 Minuten Raster!
}

function day2str($day){
  $days = "MO";
  if ($day == 0) $days="MO";
  if ($day == 1) $days="TU";
  if ($day == 2) $days="WE";
  if ($day == 3) $days="TH";
  if ($day == 4) $days="FR";
  if ($day == 5) $days="SA";
  if ($day == 6) $days="SU";
  return $days;

}

function synthTime($in){
  // converts minutes since Mon 00:00 to MO 12:34
  $day = idiv($in,24*60);
  return (day2str($day)." ".hm2str( ($in % (24*60))*60 ));
  
}

function time2dayhm($in){
  // converts minutes since Mon 00:00 to 0 12:34
  $day = idiv($in,24*60);
  
  return (array($day,($in % (24*60))*60));
  
}

function hm2str($in){
  if ($in === false) return "";
  return date("H:i",$in-60*60);
}


function parseSchedule($in){
#  var_dump($in);
  $sch = array();
  foreach ($in as $l){
    if (trim($l) == "OK") continue;
    $si = explode(" ",$l);
    if (count($si)<3) continue;
    $tm = parseTime($si[1]." ".$si[2]);
    $cmd = (trim($si[3])=="ON"?true:false);
    if ($tm !== false) $sch[$tm]=$cmd;
  }
  ksort($sch);
  $sch2 = array();
  $old = "INVALID";
  foreach($sch as $k => $v){
    if ($old !== $v){
      $sch2[$k] = $v;
      $old=$v;
    }
  }

  return $sch2;
}

function printSchedule($sch){
  foreach ($sch as $s => $cmd){
    print(synthTime($s).
      " ".($cmd?'ON':'OFF')."\n");
  }
}

function schedule2graph($sch,$outfn){
  global $daystr;
  global $graphpath;
#  var_dump($sch);

  $wechsel = array();
  foreach($sch as $in => $v){
    $i = time2dayhm($in);
    $day = $i[0];
    $wechsel[$day] = $v;
  }

  $doffset = mktime(0,0,0,1,3,2000); # Align Monday
  $fd=array();
  $fname=array();

  $wechsel = array();
  foreach($sch as $in => $v){
    $i = time2dayhm($in);
    $day = $i[0];
    $wechsel[$day+1] = $v;
  }

  for ($k=1;$k<=7;$k++) if (isset($wechsel[$k])) $wechsel[0]=$wechsel[$k];

  for ($k=1;$k<=7;$k++) if (!isset($wechsel[$k])) $wechsel[$k]=$wechsel[$k-1];

  for ($day=0;$day<=6;$day++){
    $fname[$day] = "/tmp/schedule-".time()."-".$day.".dat";
    $fd[$day] = fopen($fname[$day],"w");
    $v = $wechsel[$day];
    fwrite($fd[$day],"2000-01-03 00:00:00 ".($v?(6-$day)+0.3:(6-$day))."\n");
  }
  foreach($sch as $in => $v){
    $i = time2dayhm($in);
    $day = $i[0];
    $hm  = $i[1];
    fwrite($fd[$day],date("Y-m-d H:i:s",$hm + $doffset)." ".($v?(6-$day):(6-$day)+0.3)."\n");
    fwrite($fd[$day],date("Y-m-d H:i:s",$hm + $doffset)." ".($v?(6-$day)+0.3:(6-$day))."\n");
  }  

  for ($day=0;$day<=6;$day++){
    $v = $wechsel[$day+1];
    fwrite($fd[$day],"2000-01-03 23:59:30 ".($v?(6-$day)+0.3:(6-$day))."\n");
    fclose($fd[$day]);
  }


    $p = popen("gnuplot", "w");
    fwrite($p,"set terminal png font 'arial' 12 size 800, 450\n");
     fwrite($p,"set grid lc rgb '#aaaaaa' lt 1 lw 0,5\n");
     fwrite($p,"set title 'Schaltzeiten'\n");
     fwrite($p,"set xdata time\n");
     fwrite($p,"set xlabel 'Zeit'\n");
     fwrite($p,"set ylabel 'Reduziert / Normal'\n");
     fwrite($p,"set timefmt '%Y-%m-%d %H:%M:%S'\n");
     fwrite($p,"set format x '%H:%M'\n");
     fwrite($p,"set xtics 3600 rotate by -45\n");
     fwrite($p,"set ytics autofreq\n");
     fwrite($p,"set key outside\n");

     fwrite($p,"set output '$graphpath$outfn'\n");
     fwrite($p,"plot");
     $first=true;
     foreach ($fname as $fn){
       if (!$first) fwrite($p," , ");
       $first = false;
       $days = $daystr[substr($fn,-5,1)];
       fwrite($p," '".$fn."' using 1:3 with lines lw 6 title '".$days."' ");
     }
     fwrite($p,"\n");

     pclose($p);

     foreach ($fname as $fn) unlink($fn);

}

function formatval($sinf,$day,$on,$i,$onpadding){
  if ($onpadding){
    if ($i==0) return "";
    $i--;
  }
  $res=hm2str($sinf[$day][$on][$i]);
  return $res;
}

function schedule2html($sch,$id){
  global $daystr,$html_cols;
  $sinf = array();
  foreach($sch as $k => $v){
    $dhm = time2dayhm($k);
    $day = $dhm[0];
    $hm = $dhm[1];
    if (!isset($sinf[$day])) $sinf[$day] = array(0 => array(), 1 => array());
    $sinf[$day][$v][] = $hm;
  }

  for($day=0;$day<=6;$day++){
    $onpadding = (isset($sinf[$day][0][0]) && isset($sinf[$day][1][0]) && ($sinf[$day][0][0] < $sinf[$day][1][0]));
    # Padding mit false;
    for($k=0;$k<$html_cols;$k++){
      $sinf[$day][0][]=false;
      $sinf[$day][1][]=false;
    }
    print("<tr>");
    print("<td bgcolor=#cccccc>".$daystr[$day]."</td>");
    for ($i=0;$i<$html_cols;$i++){
      print("<td bgcolor=#cceecc><input type=text size=5 name=".$id."-".$day."-".$i."-1 value=".formatval($sinf,$day,1,$i,$onpadding)."></td>");
      print("<td bgcolor=#eecccc><input type=text size=5 name=".$id."-".$day."-".$i."-0 value=".formatval($sinf,$day,0,$i,false)."></td>");
    }
    print("</tr>\n");
  }
}


function html2sched($post,$id){
  global $html_cols;
  $schstr = "";
  $idx = 1;
  for($day=0;$day<=6;$day++){
    for ($i=0;$i<$html_cols;$i++){
      if (isset($post[$id."-".$day."-".$i."-1"]) && ($post[$id."-".$day."-".$i."-1"] != "")) {
        $schstr .= $idx." ".day2str($day)." ".$post[$id."-".$day."-".$i."-1"]." ON\n";
        $idx++;
      }
      if (isset($post[$id."-".$day."-".$i."-0"])&& ($post[$id."-".$day."-".$i."-0"] != "")) {
        $schstr .= $idx." ".day2str($day)." ".$post[$id."-".$day."-".$i."-0"]." OFF\n";
        $idx++;
      }
    }
  }
  $sch= parseSchedule(explode("\n",$schstr));
  return $sch;
}

function readSchedule($hk,$sub){
  if ($hk == "wwzirkpump") $hk="ww zirkpump";
  return parseSchedule(doEmsCommand($hk." getschedule ".$sub));
}

function programSchedule($sch,$hk,$sub){
  if ($hk == "wwzirkpump") $hk="ww zirkpump";
  $i = 1;
  foreach ($sch as $s => $cmd){

    $eqry = $hk." schedule ".$sub." $i ".synthTime($s)." ".($cmd?'ON':'OFF');
#    print($eqry."<br>\n\n");
    flush();
    $eres = doEmsCommand($eqry);
    array_pop($eres);
    $eres = end($eres);
#    print($eres."<br>\n\n");
    flush();
    if (trim($eres) != "OK") die("Error while programming!");
#    print("o");
    $i++;
  }
  for ($j=$i ; $j <= 42; $j++){
#    print(".");
    
    $dcmd = doEmsCommand($hk." schedule ".$sub." $j unset");
    array_pop($dcmd);
#    var_dump($dcmd);
    if (trim(end($dcmd)) != "OK") die("Error while erasing!");


  }
  print("\n");
}

function getActSchedule($src){
  $srch = array("ww" => "DATA: Programm Warmwasser =",
                "zir" => "DATA: Programm Zirkulationspumpe =");

  $trans = array("nach Warmwasser" => "Heizkreis",
                 "nach Heizkreisen" => "Heizkreis",
                 "Eigenes Programm" => "Eigen1");

  if (($src == "ww") || ($src == "zir")){
    $res=doEmsCommand("ww getstatus");
    foreach($res as $r){
      $r2 = str_replace($srch[$src],"",$r);
      if ($r2 != $r) return $trans[trim($r2)];
    }
    return "";

  }

  $res = doEmsCommand($src." actschedule");
  array_pop($res);
  array_pop($res);
  return (trim(array_pop($res)));
}

function getEmsStatusCodes(){
  $link=mysql_connect("localhost", "ems", "buderusems")
     or die("Keine Verbindung zur Datenbank möglich: " . mysql_error());
  mysql_query("use ems_data");
     
  $result = mysql_query("SELECT a.value as sc, b.value as fc, a.starttime as time ".
                      "FROM state_data a, state_data b ".
                      "WHERE a.sensor = 200 and b.sensor = 201 ".
                      "       and a.starttime = b.starttime ".
                      "ORDER BY time DESC" )
        or die("Abfrage fehlgeschlagen! ". mysql_error());
                 
  $res = array();
  while($row = mysql_fetch_assoc($result) ){
    $res[]=str_replace(" ","|",trim($row['time']))."|".$row['sc']."|".$row['fc'];
  }                                                   
  return $res;
}                                                   

function ldat_parse($s){

  if (substr($s,-4) == " min"){
    $h = $s;
    $s = str_replace(" min","",$s);
    $s2 = ($s % (60));
    $s = $s - $s2;
    $s = $s / (60);
    $s = $s."h ".$s2."min";
    }
  return $s;

}

function getEmsLiveData(){
  clearstatcache();
  exec("tail -5000 /var/log/ems/data.log",$in);
  $inf = array();
  $hide = array("Abgastemperatur","HK1 Raumtemperatur-Änderungsgeschwindigkeit","Konfiguration","Warmwasser-Isttemperatur (Messstelle 2)",
          "Ausschaltoptimierung","Einschaltoptimierung","Estrichtrocknung",
          "Schaltuhr HK1"
          );
  foreach ($in as $z){
    $z = utf8_decode($z);
    $z = str_replace("?","",$z);
    $z = str_replace("DATA: ","",$z);
    $z = str_replace("Warmwassersystems:","Warmwassersystems=",$z);
    $p = strpos($z,"=");
    $zi = array(trim(substr($z,0,$p)),substr($z,$p+2));
    if (($zi[0]) && (strpos(" ".$z,"Un")==FALSE) && !in_array(trim($zi[0]),$hide))  $inf[trim($zi[0])] = trim(ldat_parse(trim($zi[1])));
  }

  ksort($inf);
  return $inf;
}