<?php

if(@!$istr=$_POST['istr']){
  $istr="101010";
  }

?>

<form action=chunk_test.php method=post>
	<input type=text size=40 name=istr value="<?php echo $istr;?>">
	<input type=submit>
</form>

<?php
require_once("config.php");

load_all("basic");
load_all("basic");

//ar_dump($uscript_lib,"lib");


$instr="d>b8s4";
$instr2="10f0f";

$rchunks=parse_brackets($istr);
parse_prep_words($rchunks);
ar_dump($rchunks,"rchunk");


/*
$plines=array();
$plines[]=$rchunk;
$page_svg=draw_svg_page($plines,0,0);
echo $page_svg;

*/
?>

