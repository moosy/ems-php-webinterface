<?

$rights = array("login"  => array(),
                "public" => array());

$menus=array(

  array("Buderus EMS","main.php","Heizung",
       array(
             "Livestatus" => "a_emslive.php",
             "Schaltpunkte" => "a_emssched.php",
             "Heizkurve" => "a_emshk.php",
             "Einstellungen" => "a_emsset.php",
             "Fehlerspeicher" => "a_emserr.php",
             "Protokoll" => "a_emslog.php",
             "Statistik" => "a_emstime.php",
             "HIDDEN7" => "a_emshour.php",
             "HIDDEN6" => "a_emstest.php",
             "HIDDEN4" => "a_emsday.php",
             "HIDDEN5" => "a_emsdet.php",
             "HIDDEN1" => "a_emshw.php",
             "HIDDEN2" => "a_emsweek.php",
             "HIDDEN3" => "a_emsmonth.php"),
       "public"),
       
);
?>