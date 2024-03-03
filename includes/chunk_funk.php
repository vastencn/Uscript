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
  $new_chunk['defmap']=array();

  return $new_chunk;
  }

function gap_chunk($len){
  if(!is_numeric($len))return NULL;
  if($len<=0)return NULL;
  $rchunk=create_chunk();
  $rchunk['type']='space';
  $rchunk['string']="_$len";
  $rchunk['struct']=NULL;
  $rchunk['svg']="<g></g>";
  $rchunk['drawn']=1;
  $rchunk['height']=1;
  $rchunk['width']=$len;
  $rchunk['defmap']=NULL;
  return $rchunk;
  }
function pos_chunk($len){
  if(!is_numeric($len))return NULL;
  if($len<=0)return NULL;
  $rchunk=create_chunk();
  $rchunk['type']='pos';
  $rchunk['string']="_$len";
  $rchunk['struct']=NULL;
  $rchunk['svg']="<g></g>";
  $rchunk['drawn']=1;
  $rchunk['height']=1;
  $rchunk['width']=$len;
  $rchunk['defmap']=NULL;
  return $rchunk;
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
  $rchunk['defmap']=create_dmap(@$cdat['spelling'],$cdat['width'],$cdat['height']);
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
  global $chunk_spacing,$defmap_on;
  if($spacing>=0){
    $do_space=$spacing;
    }else{
    $do_space=$chunk_spacing;
    }
  if(!chunk_is_drawable($child))return NULL;

  $xpos=$parent['width'];
  if($xpos>0)$xpos+=$do_space;

  if($defmap_on){
    defmap_append($parent,$child,$xpos);
    }

  @$parent['svg']=@$parent['svg'].draw_svg_symbol($child['svg'],0,$xpos);

  @$parent['width']=$xpos+$child['width'];
  if(@$parent['height']<$child['height']){
    $parent['height']=$child['height'];
    }
  return TRUE;
  }
?>