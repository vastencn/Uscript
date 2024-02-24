<?php

$curent_render_fname="render_id.txt";
$max_render_id=100;
$render_img_prefix="rimg";
$render_txt_prefix="rtxt";

function pre_render_into_chunk($str){
  $mkup_str=pre_render_into_mkup($str);
  $elsa=render_uscript_text($mkup_str);
  return $elsa;
  }

function pre_render_into_mkup($str){
  global $render_img_prefix;
  return render_id_to_mkup( pre_render($str) );
  }

function render_id_to_mkup($id){
  global $render_img_prefix;
  return "imgrender_".$render_img_prefix.$id;;
  }

function insert_pre_renders($str){
  $sar=explode("|",$str);
  if(count($sar)<2)return $str;

  $arc=count($sar);
  for($i=1;$i<$arc;$i+=2){
  	$sar[$i]=pre_render_into_mkup($sar[$i]);
    }
  $rstr=implode("",$sar);
  return $rstr;
  }

function new_render_id(){
  global $render_dir,$curent_render_fname,$max_render_id;

  $id_fname=$render_dir.$curent_render_fname;
  $id=file($id_fname);

  $id=implode($id);
  $id=preg_replace("/[^0-9]/","",$id);

  if(!ctype_digit($id)||strlen($id<1))$id="1";

  $id++;

  if($id>$max_render_id)$id=1;

  file_dump($id_fname,$id);
  return $id;
  }

function pre_render($str){
  
  $str=str_replace("NL","\n",$str);
  $str=str_replace("\\n","\n",$str);

  $svg=multi_line_render($str);
  $rid=store_pre_render($svg,$str);

  return $rid;
  }

function store_pre_render($svg,$txt){
  global $render_dir,$render_img_prefix,$render_txt_prefix;
  $rid=new_render_id();
  
  $svg_path=$render_dir.$render_img_prefix.$rid.".svg";
  $txt_path=$render_dir.$render_txt_prefix.$rid.".txt";
  
  file_dump($svg_path,$svg);
  file_dump($txt_path,$txt);
  return $rid;
  }


?>