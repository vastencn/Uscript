<?php

$z=NULL;
function plus1(&$x){
	global $z;
  $z=&$x;
  $x+=1;
  echo "[$z]";
}


macro {
*T_VARIABLE
} >> {
plus1(T_VARIABLE)
}
$x=1;

$y=&$x;

$x=2;

echo "$x";
plus1($x);
echo "$x";
*$x;
echo "$x";

?>