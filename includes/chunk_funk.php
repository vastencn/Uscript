<?php
function create_chunk($cstr=""){
  $new_chunk=array();

  $new_chunk['type']=NULL;
  $new_chunk['string']=$cstr;
  $new_chunk['struct']=NULL;
  $new_chunk['opt']=array();
  $new_chunk['svg']="";
  $new_chunk['drawn']=0;
  $new_chunk['height']=0;
  $new_chunk['width']=0;

  return $new_chunk;
  }

function char2chunk($cdat){
  if(!$cdat)return NULL;
  if(!is_svg_drawable($cdat))return NULL;
  $rchunk=create_chunk();
  $rchunk['type']='char';
  $rchunk['string']=@$cdat['spelling'];
  $rchunk['struct']=$cdat;
  $rchunk['svg']=$cdat['svg'];
  $rchunk['drawn']=1;
  $rchunk['height']=$cdat['height'];
  $rchunk['width']=$cdat['width'];
  return $rchunk;
  }

function chunk_is_drawable($chunk){
  if(!$chunk)return NULL;
  if(@$chunk['width']<1)return NULL;
  if(@$chunk['height']<1)return NULL;
  if(strlen(@$chunk['svg'])<1)return NULL;
  return TRUE;
  }


function chunk_append(&$parent, $child, $spacing=-1){
  global $chunk_spacing;
  if($spacing>=0){
    $do_space=$spacing;
    }else{
    $do_space=$chunk_spacing;
    }
  if(!chunk_is_drawable($child))return NULL;

  $xpos=$parent['width'];
  if($xpos>0)$xpos+=$chunk_spacing+$do_space;

  @$parent['svg']=@$parent['svg'].draw_svg_symbol($child['svg'],0,$xpos);

  @$parent['width']=$xpos+$child['width'];
  if(@$parent['height']<$child['height']){
    $parent['height']=$child['height'];
    }
  return TRUE;
  }
?>