<?php

if(@!$istr=$_POST['istr']){
  $istr="101 sub(101 sub( 10 01) 10) 111 000 sub ( 110011 )";
  $istr="010 (101)subof 111 ";
  }

?>

<form action=text_test.php method=post>
	<input type=text size=40 name=istr value="<?php echo $istr;?>">
	<input type=submit>
</form>

<?php
require_once("config.php");

$elsa=render_uscript_text($istr,$car);

 $lar=array();

  $lar['height']=$elsa['height'];
  $lar['width']=$elsa['width'];
  $lar['svg']=$elsa['svg'];

  $plines=array();
  $plines[]=$elsa;
  echo draw_svg_page($plines,0,0,$elsa['width'],$elsa['height']);



?>

