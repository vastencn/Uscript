<?php

function load_presave($id){
  global $presave_dir,$render_dir;

  if(!safe_fname($id))return NULL;
  
  $presaved=NULL;
  if(substr($id,0,4)=="rimg"){
  	$idnum=substr($id,4);
  	$folder=$render_dir;
  	$imgpre="imgrender_";
  	$txt_fname="rtxt".$idnum.".txt";
  	$svg_fname="rimg".$idnum.".svg";
  	$img_name=$id;
    }else{
    $presaved=TRUE;
    $folder=$presave_dir;	
  	$imgpre="imgpresave_";
  	$txt_fname=$id.".txt";
  	$svg_fname=$id.".svg";
  	$img_name=$id;
    }  

  $royal_chambers=$folder.$txt_fname;
  $throne_room=$folder.$svg_fname;
  $royal_throne=$imgpre.$id;


  if(!$fc=file($royal_chambers)){
  	return NULL;
    }
  $anna=implode("",$fc);


  if($crown=img_search($royal_throne)){
    }else{    	
    $crown['svg']=multi_line_render($anna);
    file_dump($royal_throne,$crown['svg']);
    }

  
  $crown['spelling']=$img_name;
  $crown['content']=$img_name;
  $crown['text']=$anna;

  return $crown;
  }


function render_presave($id){
  global $presave_dir;

  if(!safe_fname($id))return NULL;


  $royal_chambers=$presave_dir.$id.".txt";
  $throne_room=$presave_dir.$id.".svg";
  $royal_throne="imgpresave_".$id;

  if(!$fc=file($royal_chambers)){
    return NULL;
    }
  $anna=implode("",$fc);


  $crown['svg']=multi_line_render($anna);

  file_dump($throne_room,$crown['svg']);

  return $crown['svg'];
  }
 
?>