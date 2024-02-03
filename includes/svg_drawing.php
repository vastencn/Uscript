<?php
//going to be quick and lazy here
//instead of building a structure for the drawing, just going to have fixed header and footer strings with only x,y size dynamic
//
// draw functions have optional x,y values
// y first then x, because its more often you will want to set y and leave x as 0


$px2mm=1;
$line_i=1;

//width and height in px
function svg_header($width=500, $height=500){
  global $px2mm;
  $width_mm=$width/$px2mm;
  $height_mm=$height/$px2mm;

  $rstr="<svg
   width=\"$width\"
   height=\"$height\"
   viewBox=\"0 0 $width_mm $height_mm\"
   version=\"1.1\"
   id=\"svg3807\"
   inkscape:version=\"1.1.2 (b8e25be833, 2022-02-05)\"
   sodipodi:docname=\"0.svg\"
   xmlns:inkscape=\"http://www.inkscape.org/namespaces/inkscape\"
   xmlns:sodipodi=\"http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd\"
   xmlns=\"http://www.w3.org/2000/svg\"
   >";

  return $rstr;
  }

function svg_footer(){
  return "</svg>";
  }

function create_svg_line(){
  $sline=array();
  $sline['width']=0;
  $sline['height']=0;
  $sline['svg']="";
  return $sline;
  }

function draw_svg_line($sline,$y=0,$x=0){
  global $line_i;

  $rstr="<g
     inkscape:label=\"Line $line_i\"
     inkscape:groupmode=\"layer\"
     id=\"layer L$line_i\"
     transform=\"translate(0,0)\">";
  $rstr.=$sline['svg'];
  $rstr.="</g>";
  $line_i++;

  return $rstr;
  }

function draw_svg_page($svg_lines,$y=0,$x=0){
  global $line_i;
  $line_i=1;
  $page_svg=svg_header();
  foreach($svg_lines as $tline){ 
    $page_svg.=draw_svg_line($tline,$y,$x);
    $y+=$tline['height'];
    $line_i++;
    }
  $page_svg.=svg_footer();
  return $page_svg;
  }



?>