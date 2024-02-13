<?php

function create_defmap_imap($name="",$href=""){
  $imap=array();
  $imap['name']=$name;
  $imap['href']=$href;
  $imap['html']="";
  return $imap;
  }

function create_dmap($name="",$w=0,$h=0,$xo=0){
  $dmap=array();
  $dmap[]=create_dmap_entry($name,$w,$h,$xo);
  return $dmap;
  }

function create_dmap_entry($name="",$w=0,$h=0,$xo=0){
  $dmap_entry=array();
  $dmap_entry['xoff']=$xo;
  $dmap_entry['width']=$w;
  $dmap_entry['height']=$h;
  $dmap_entry['defname']=$name;
  return $dmap_entry;
  }

function defmap_append(&$iduna,$elsa,$xoff){
  if(!is_array(@$iduna['defmap']))$iduna['defmap']=array();
  if(!is_array(@$elsa['defmap']))return NULL;
  foreach($elsa['defmap'] as $entry){
    $entry['xoff']+=$xoff;
    $iduna['defmap'][]=$entry;
    }
  return;
  }

function defmap_direct_append(&$iduna,$elsa,$xoff){
  if(!is_array(@$iduna))$iduna=array();
  if(!is_array(@$elsa))return NULL;
  foreach($elsa as $entry){
    $entry['xoff']+=$xoff;
    $iduna[]=$entry;
    }
  return;
  }

function defmap_imap($defmap,$lh=NULL,$mapname="imap",$href_prefix=NULL){

  if(!$href_prefix)$href_prefix="#";
  $rstr="<map name=$mapname>\n";

  if($lh)$cy=$lh/2;

  foreach($defmap as $elsa){
    $y1=0;
    $x1=$elsa['xoff'];

    $y2=$elsa['height'];
    $x2=$x1+$elsa['width'];

    if($lh){
      $voff=($y2/2)-$cy;
      $y1-=$voff;
      $y2-=$voff;
      }
    $href=$href_prefix.$elsa['defname'];
    $estr=" <area shape=rect coords=\"$x1,$y1,$x2,$y2\" href=\"$href\" title=\"".$elsa['defname']."\">\n";
    $rstr.=$estr;
    }

  $rstr.="</map>\n";
  return $rstr;
  }
?>