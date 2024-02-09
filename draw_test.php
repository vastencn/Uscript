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
load_all("basic");


$num_chunk=draw_word($istr);

$brak_chunk=sub_brak($num_chunk);
ar_dump($brak_chunk,"bchunk");

$plines=array();
$plines[]=$brak_chunk;
$plines[]=$brak_chunk;
$plines[]=$brak_chunk;
$page_svg=draw_svg_page($plines,100,100);
echo $page_svg;

exit();

$plines=array();
$plines[]=$rchunk;
$page_svg=draw_svg_page($plines,0,0);

?>

 <svg height="210" width="300" xmlns="http://www.w3.org/2000/svg">



<?php

$par=array();
$par[]=array(100,100);
$par[]=array(80,100);
$par[]=array(80,80);
$par[]=array(100,80);

//echo svg_polyline($par,2);

//echo svg_vcup(200,200,50,20,2);
echo draw_svg_symbol(sub_brak_draw_open(),100,100);

?>

</svg>
