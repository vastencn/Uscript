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

function svg_hzigzag($x,$y,$w,$h,$w2=5,$xtra=0,$s=2){
  $pts=array();


  $center=$w/2;
  $pts[]=array($x,$y);
  $pts[]=array($x+$center+$w2,$y);
  $pts[]=array($x+$center-$w2,$y+$h);
  $pts[]=array($x+$w,$y+$h);

  return svg_polyline($pts,$s);
  }

function svg_arc($x1,$y1,$xdir,$ydir,$size=5,$flip=0){
  $x2=$x1+$xdir*$size;
  $y2=$y1+$ydir*$size;
  $str="  <path d=\"M $x1 $y1
           A $size $size 0 0 $flip $x2 $y2\"
           stroke=\"black\" fill=\"none\" stroke-width=\"2\"/>";
  return $str;
  }


?>