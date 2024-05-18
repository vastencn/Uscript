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

function svg_hzigzag($x,$y,$w,$h,$w2=.3,$s=2){
  $pts=array();


  $center=$w/2;
  $coff=($w2/2)*$center;
  $pts[]=array($x,$y);
  $pts[]=array($x+$center+$coff,$y);
  $pts[]=array($x+$center-$coff,$y+$h);
  $pts[]=array($x+$w,$y+$h);

  return svg_polyline($pts,$s);
  }


?>