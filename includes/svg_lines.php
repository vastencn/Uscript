<?php 
//No error-checking added in here
//could add, but we already have a growing stack and function call
//for now I will leave these vulnerale to spewing errors if bad data is fed into them



//x,y,length,stroke width
function svg_vline($x=0,$y=0,$l=5,$s=2){
  $y2=$y+$l;
  $line_svg="<line x1=\"$x\" y1=\"$y\" x2=\"$x\" y2=\"$y2\" style=\"stroke:black;stroke-width:$s\" />";
  return $line_svg;
  }

//x,y,length,stroke width
function svg_hline($x=0,$y=0,$l=5,$s=2){
  $x2=$x+$l;
  $line_svg="<line x1=\"$x\" y1=\"$y\" x2=\"$x2\" y2=\"$y\" style=\"stroke:black;stroke-width:$s\" />";
  return $line_svg;
  }


//exploding/expanding lines, expands out from origin in both directions
function svg_vexline($x=0,$y=0,$l=5,$s=2){
  $y1=$y-$l;
  $y2=$y+$l;
  $line_svg="<line x1=\"$x\" y1=\"$y1\" x2=\"$x\" y2=\"$y2\" style=\"stroke:black;stroke-width:$s\" />";
  return $line_svg;
  }

function svg_hexline($x=0,$y=0,$l=5,$s=2){
  $x1=$x-$l;
  $x2=$x+$l;
  $line_svg="<line x1=\"$x1\" y1=\"$y\" x2=\"$x2\" y2=\"$y\" style=\"stroke:black;stroke-width:$s\" />";
  return $line_svg;
  }



function svg_polyline($pts,$s){
  $pstr="";
  foreach($pts as $tpt){
    $pstr.=$tpt['0'].",".$tpt['1']." ";
    }
  $svg_str="<polyline points=\"$pstr\" style=\"fill:none;stroke:black;stroke-width:$s\" />";
  return $svg_str;
  }


// a vertical right angle cup shape
//bottom center x,y width,depth stroke width
function svg_vcup($x=0,$y=0,$w=10,$d=10,$s=2){
  $hw=$w/2;
  $b1=array($x-$hw,$y);
  $b2=array($x+$hw,$y);
  $t1=$b1;$t1[1]-=$d;
  $t2=$b2;$t2[1]-=$d;
  $pts=array($t1,$b1,$b2,$t2);
  return svg_polyline($pts,$s);
  }

function svg_hcup($x=0,$y=0,$w=10,$d=10,$s=2){
  $hw=$w/2;
  $b1=array($x,$y-$hw);
  $b2=array($x,$y+$hw);
  $t1=$b1;$t1[0]-=$d;
  $t2=$b2;$t2[0]-=$d;
  $pts=array($t1,$b1,$b2,$t2);
  return svg_polyline($pts,$s);
  }

?>