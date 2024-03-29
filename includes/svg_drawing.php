<?php
//going to be quick and lazy here
//instead of building a structure for the drawing, just going to have fixed header and footer strings with only x,y size dynamic
//
// draw functions have optional x,y values
// y first then x

$px2mm=1;
$line_i=1;
$page_line_spacing=5;

function is_svg_drawable($tobj){
  //universal function for drawing check
  //drawble things must at leas have height, width and and svg string
  if(@$tobj['height']<1)return NULL;
  if(@$tobj['width']<1)return NULL;
  if(strlen(@$tobj['height'])<1)return NULL;
  return TRUE;
  }

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

function create_svg_line($svg="",$w=0,$h=0){
  $sline=array();
  $sline['width']=$w;
  $sline['height']=$h;
  $sline['svg']=$svg;
  return $sline;
  }

function is_valid_svg_line($sline){
  if(!$sline)return NULL;
  if(@count($sline)<3)return NULL;
  if(@$sline['height']<1)return NULL;
  if(@$sline['width']<1)return NULL;
  if(strlen(@$sline['svg'])<1)return NULL;
  return TRUE;
  }

function draw_svg_symbol($svg_str,$y=0,$x=0){
  $rstr="<g transform=\"translate($x,$y)\">";
  $rstr.=$svg_str;
  $rstr.="</g>";
  return $rstr;
  }

function draw_svg_scaled($svg_str,$y=1,$x=1){
  $rstr="<g transform=\"scale($x,$y)\">";
  $rstr.=$svg_str;
  $rstr.="</g>";
  return $rstr;
  }

function draw_svg_line($sline,$y=0,$x=0,&$len){
  global $line_i;
  $rstr="<g
     inkscape:label=\"Line $line_i\"
     inkscape:groupmode=\"layer\"
     id=\"layer L$line_i\"
     transform=\"translate($x,$y)\">";
  $rstr.=$sline['svg'];
  $rstr.="</g>";
  $line_i++;

  return $rstr;
  }

function draw_svg_page($svg_lines,$y=0,$x=0,$w=NULL,$h=NULL){
  global $line_i,$page_line_spacing;
  $line_i=1;
  $mw=0;
  $page_svg="";
  foreach($svg_lines as $tline){ 
  	$lh=$tline['height'];
    $lw=$tline['width'];
    if($lw>$mw)$mw=$lw;
  	$lhh=$lh/2;
  	if(!is_valid_svg_line($tline))continue;
    $page_svg.=draw_svg_line($tline,$y+$lhh,$x,$len);
    $y+=$lh+$page_line_spacing;
    $line_i++;
    }
  $y-=$page_line_spacing;
  if(!$h)$h=$y;
  if(!$w)$w=$mw;
  $page_svg=svg_header($w,$h).$page_svg.svg_footer();
  return $page_svg;
  }


function draw_svg_imgline($line,$mname=""){
  $lines=array($line);
  $svg_str=draw_svg_page($lines,0,0,$line['width'],$line['height']);
  $svg_str=str_replace("#","%23",$svg_str);

  $rstr="<img src='data:image/svg+xml;charset=utf-8,$svg_str' usemap=\"#$mname\"  class=\"mapper showcoords noborder iopacity50 icolorff0000\" />\n";
  return $rstr;
  }




function import_svg($path){
  if(!file_exists($path))return NULL;
  $far=file($path);
  foreach($far as $tline){
    if($str=strstr(strtolower($tline),"viewbox")){
      $sar=explode("\"",$str);
      $nar=explode(" ",$sar[1]);
      $width=round($nar[2],1);
      $height=round($nar[3],1);
      break;
      }
    }

  if($width<1||$height<1)return NULL;

  $svg_str=implode("",$far);

  //now lets crop out the highest level object group
  $svg_str=strstr($svg_str,"<g");
  $svg_str=strrev(strstr(strrev($svg_str),"g/<"));
  $svg_str=$svg_str.">";

  $rec=array();
  $rec['path']=$path;
  $rec['height']=$height;
  $rec['width']=$width;
  $rec['svg']=$svg_str;
  $rec['loaded']=TRUE;  
  return $rec;
  }


?>