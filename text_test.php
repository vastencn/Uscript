<html>
<head>
 <title>text test</title>
  
	<script type="text/javascript" src="js/cvi_tip_lib.js"></script>
	<script type="text/javascript" src="js/maputil.js"></script>
	<script type="text/javascript" src="js/mapper.js"></script>
</head>
<body>

<?php

if(@!$istr=$_POST['istr']){
  $istr="101 sub(101 sub( 10 01) 10) 111 000 sub ( 110011 )";
  $istr="010 (101)subof 111 ";
  }

?>
<div>
	<table>
<tr><td>



<form action=text_test.php method=post>
	<input type=text size=40 name=istr value="<?php echo $istr;?>">
	<input type=submit>
</form>

</td></tr>
<tr>
	<td>


<?php
require_once("config.php");

$elsa=render_uscript_text($istr,$car);

 $lar=array();

  $lar['height']=$elsa['height'];
  $lar['width']=$elsa['width'];
  $lar['svg']=$elsa['svg'];

  $plines=array();
  $plines[]=$elsa;
  $svg_str=draw_svg_page($plines,0,0,$elsa['width'],$elsa['height']);

  $svg_str=str_replace("#","%23",$svg_str);
  echo "<img src='data:image/svg+xml;charset=utf-8,$svg_str' usemap=\"#imap\"  class=\"mapper showcoords noborder iopacity50 icolorff0000\" />";


?>
</td></tr></table>
</div>
</body>
</html>
