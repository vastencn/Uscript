<?php

require_once("config.php");


$instr="h>b10";
$rchunk=draw_symbol($instr);
print_r($rchunk);

/*
echo "<hr>";
print_r(
      parse_brackets("hello(middle)goodbye  a(bc{d[e]f})")
    );
echo "</pre>";
*/

?>

<svg height="200" width="1000">
    <line x1="0" y1="0" x2="200" y2="0" style="stroke:rgb(255,0,0);stroke-width:1" />
    <line x1="0" y1="20" x2="200" y2="20" style="stroke:rgb(255,0,0);stroke-width:1" />


<?php

if($rchunk){
  echo $rchunk['svg'];
  }

?>


</svg>