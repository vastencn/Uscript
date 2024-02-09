<?php

require_once("config.php");


$instr="d>b8s4";
$instr="0123456789abcdef";

if(load_chars("basic")){
  echo "[Chars GOOD FOLDER NAME]";
  }else{
  echo "[Chars BAD FOLDER NAME]";
  }

if(load_overrides("overrides")){
  echo "[over GOOD FOLDER NAME]";
  }else{
  echo "[over BAD FOLDER NAME]";
  }

ar_dump($uscript_overrides,"over");

echo "((".search_overrides("1d")."))";


$rchunk=draw_word($instr);
$schunk=draw_string("01 01");
ar_dump($schunk,"sch");
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
  echo $schunk['svg'];
  }

?>


</svg>