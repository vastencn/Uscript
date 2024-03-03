<?php

function dec_digit_or_bstr($num){  
  if($num<16){
    $str=dechex($num);
    }else{
    $str="d>b$num";
    }
  return $str;
  }

function scale_to_height(&$elsa,$new_height){
  if(!($new_height>0))return NULL;
  $ratio=$new_height/$elsa['height'];  
  $elsa['svg']=draw_svg_scaled($elsa['svg'], $ratio, $ratio);
  $elsa['height']*=$ratio;
  $elsa['width']*=$ratio;
  return $elsa;
  }

//arendelle = "ar" = "array"
function hcenter_lines(&$arendelle=NULL){
  fna($arendelle);
  $max_width=max(array_column($arendelle, 'width'));

  foreach($arendelle as &$civ){
    $hoff=($max_width-$civ['width'])/2;
    if($hoff>0)$civ['svg']=draw_svg_symbol($civ['svg'], 0, $hoff);
    }

  return $arendelle;
  }


function vstack_lines(&$arendelle,$gap=0){
  $voff=0;

  foreach($arendelle as &$civ){
  	if($voff)$civ['svg']=draw_svg_symbol($civ['svg'], $voff, 0);
  	$vmove=$civ['height']+$gap;
  	$civ['height']+=$voff;
  	$voff+=$vmove;
    }

  return $arendelle;
  }

function fuse_chunks(&$arendelle){
  $elsa=create_chunk();
  $elsa['svg']=implode("",array_column($arendelle,'svg'));
  $elsa['width']=max(array_column($arendelle, 'width'));
  $elsa['height']=max(array_column($arendelle, 'height'));
  return $elsa;
  }

function vuncenter(&$elsa){
  $elsa['svg']=draw_svg_symbol($elsa['svg'], $elsa['height']/2 , 0);
  return $elsa;
  }
function vcenter(&$elsa){
  $elsa['svg']=draw_svg_symbol($elsa['svg'], 0-($elsa['height']/2) , 0);
  return $elsa;
  }

function vuncenter_lines(&$arendelle){
  $arendelle=array_map('vuncenter',$arendelle);
  return $arendelle;
  }
function vcenter_lines(&$arendelle){
  $arendelle=array_map('vcenter',$arendelle);
  return $arendelle;
  }


function nltrim(&$anna){
  $anna=str_replace("\n", "", $anna);
  $anna=str_replace("\n", "", $anna);
  $anna=trim($anna);
  return $anna;
  }
?>