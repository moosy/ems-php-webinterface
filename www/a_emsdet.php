<html>
<head>
<META http-equiv="Expires" CONTENT="0">
<link href="/moosy.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0"
rightmargin="0" marginwidth="0" marginheight="0">
<h3>EMS-Rohdaten</h3>
<div id=inhalt></div>
<script type="text/javascript">
function showinfo()
 {
   xmlhttpI=new XMLHttpRequest();
   xmlhttpI.onreadystatechange=function()
   {
   if (xmlhttpI.readyState==4 && xmlhttpI.status==200)
     {
        document.getElementById("inhalt").innerHTML=xmlhttpI.responseText;
     }
   }
 xmlhttpI.open("GET","ajax.php?seite=emsdetail.ajax",true);
 xmlhttpI.send();
 }
setInterval(function(){showinfo()},500);
</script>
</body>
</html>
