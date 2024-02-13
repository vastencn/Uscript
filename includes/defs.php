<?php
//With defintions we dont load them all because they are not needed in the same volume per render as characters or braks(often not used at all)
//so at load tme we just initialize the list of searchable folders in priority
//each request for a def searchs for a match
//foudn defintions are used but not buffered automatically so another call to the serach function will search again from scratch
//if you wish to buffer for reuse you can do that on top of this library or add the feature later
$active_def_dirs=array();

function activate_def_folder($dname){
  global $defs_dir,$active_def_dirs;

  $dname=strtolower($dname);  
  if(@strlen($dname)<1)return NULL;
  if(preg_match('/[^0-9a-z]/', $dname))return NULL;

  $dpath=$defs_dir.$dname;
  if(!is_dir($dpath))return NULL;

  $active_def_dirs[]=$dname;
  return TRUE;
  }

function def_not_found($word){
  return array("0","$word definition not found");
  }

function search_def($word){
  global $defs_dir,$active_def_dirs,$dslash;
  $word=strtolower($word);  
  if(@strlen($word)<1)return NULL;
  if(preg_match('/[^0-9a-z]/',$word))return NULL;

  $fname=$word.".txt";
  foreach($active_def_dirs as $tdir){
  	$tpath=$defs_dir.$tdir.$dslash.$fname;
    if($def=load_def($tpath)){
      return $def;
      }
    }
  return NULL;
  }

function load_def($fpath){
  if(!file_exists($fpath))return NULL;
  $lar=file($fpath);
  $car=array();
  foreach($lar as $line){
    if(substr($line,0,2)=="//")continue;
    $car[]=$line;
    }
  
  if(count($car)<1)return array("","");

  $rar=array();
  
  $rar['uscript']=$car[0];
  unset($car[0]);
  
  $rar['text']=implode("<br>",$car);
  return $rar;
  }

?>