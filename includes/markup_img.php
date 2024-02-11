<?php

//fetch an image
//_ is replace with the folder delim slash
//only alphanumeric plus _ noithing else
//and first char must be alphanumeric
function img_search($oname){
  global $dslash,$img_dir;

  if(strlen($oname)<4)return NULL;
  if(substr($oname,0,3)!="img")return NULL;
  if(!ctype_alpha(substr($oname,3,1)))return NULL;
  if(preg_match('/[^0-9a-z_.]/',$oname))return NULL;

  $iname=substr($oname,3);



  $fname=str_replace("_",$dslash,$iname);
  $fpath=$img_dir.$fname.".svg";
  
  if(!$svg=import_svg($fpath))return NULL;

  $chunk=create_chunk($oname);
  $chunk['type']="img";
  $chunk['svg']=draw_svg_symbol($svg['svg'],0-$svg['height']/2,0);
  $chunk['width']=$svg['width'];
  $chunk['height']=$svg['height'];
  $chunk['drawn']=TRUE;
  return $chunk;
  }

?>