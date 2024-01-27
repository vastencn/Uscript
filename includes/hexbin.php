<?php

$hexs=array(
          "0000",
          "0001",
          "0010",
          "0011",
          "0100",
          "0101",
          "0110",
          "0111",
          "1000",
          "1001",
          "1010",
          "1011",
          "1100",
          "1101",
          "1110",
          "1111"
        );



function arb_hexbin($hexv){
  global $hexs;
  $car=str_split(strtolower($hexv));
  $str="";
  for($i=0;$i<count($car);$i++){
    $cv=ord($car[$i]);
    if($cv>=48&&$cv<=57){
      $str.=$hexs[$cv-48];
      }else if($cv>=97&&$cv<=102){
      $str.=$hexs[$cv-87];
      }else{
      $str.=$car[$i];  
      }
    }
  return $str;
  }
?>
	