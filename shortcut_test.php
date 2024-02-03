<?php

require_once("config.php");

if(load_char_folder("basic")){
  echo "GOOD FOLDER NAME";
  }else{
  echo "BAD FOLDER NAME";
  }

$r=load_shortcuts("basic");
if($r){
  echo "basic shortcuts loaded!";
  }else{
  echo "basic shortcuts load failed!";
  }



$sword="zero";
$rez=symbol_search($sword);
ar_dump($rez,"rez");
ar_dump($uscript_lib,"symbol lib");
ar_dump($uscript_shortcuts,"shortcut lib");
?>


</svg>