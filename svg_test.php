<?php

if(@!$istr=$_POST['istr']){
  $istr="101010";
  }

?>

<form action=svg_test.php method=post>
	<input type=text size=40 name=istr value="<?php echo $istr;?>">
	<input type=submit>
</form>

<?php
require_once("config.php");

load_chars("basic");
load_shortcuts("basic");
load_brakets("basic");

//ar_dump($uscript_lib,"lib");


$instr="d>b8s4";
$instr2="10f0f";

$rchunk=draw_string($istr);
//$schunk=draw_string("01 01");

//ar_dump($rchunk,"rchunk");

echo "<br>";

$plines=array();
$plines[]=$rchunk;
$page_svg=draw_svg_page($plines,0,0);
echo $page_svg;
?>

