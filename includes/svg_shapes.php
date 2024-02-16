<?php

function svg_circle($x,$y,$r,$stroke,$fill="none"){
  $cx=$x;
  $cy=$y;
  $cr=$r-$stroke/2;
  return "<circle cx=\"$cx\" cy=\"$cy\" r=\"$cr\" stroke=\"black\" stroke-width=\"$stroke\" fill=\"$fill\" />";
  }


function svg_dot($x,$y,$r){
  return svg_circle($x,$y,$r,0,"black");
  }

?>