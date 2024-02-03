<?php

//for now shortcut indexes are stored in the root chars folder
//perhaps later they could also be stored within char sub directories so default shortcuts can be loaded when a chars folder is loaded

$uscript_shortcuts=array();
$shortcut_chars="!abcdefghijklmnopqrstuvwxyz0123456789";  //exclaimation mark is for non-alphanumeric
$shortcut_ar=str_split($shortcut_chars);
foreach($shortcut_ar as $schar){
  $uscript_shortcuts[$schar]=array();
  }



function load_shortcuts($shortcuts_file_name){
  global $chars_dir,$uscript_shortcuts;
  //only alpha numeric of course
  //if you want to use subfolders or to reference folders in other locations, then disable or modify this check
  if(strlen($shortcuts_file_name)<1 || preg_match('/[^0-9a-z]/', $shortcuts_file_name)){
    return NULL;
    }

  $file_path=$chars_dir.$shortcuts_file_name.".txt";
  if(!file_exists($file_path)){
    return NULL;
    }

  $records=array();
  $flines=file($file_path);

  //chevck all incoming lines and only accept properly formatted lines
  //just strip all unacceptabe chars
  foreach($flines as $tline){
    $tar=explode(",",strtolower($tline));

    //mustc be 2 elements
    if(count($tar)!=2){
      continue;
      }

    //strip non alphanum from the word
    $tar[1]=preg_replace("/[^a-z0-9]/", "", strtolower($tar[1]));


    //make sure it meets the ciriteria to be a valid record
    if(
    	//both not empty
    	strlen($tar[0])>0
    	&&
    	(strlen($tar[1])>0 || $tar[1]=="0" )
      ){
      
      //its a good record, add it
      $nrec=array();
      $nrec['shortcut']=$tar[0];
      $nrec['target']=$tar[1];
      $records[]=$nrec;
      }
    }

  //import them into the global shortcut 
  foreach($records as $irec){
    $flet=substr($irec['shortcut'],0,1);
    $uscript_shortcuts[$flet][]=$irec;
    }

  return TRUE;
  }

function search_shortcut($search_str){
  global $uscript_shortcuts;
  if(strlen($search_str)<1)return NULL;
  $flet=substr($search_str,0,1);
  if(!ctype_alnum($flet))$flet="!";
  foreach($uscript_shortcuts[$flet] as $srec){
    if($srec['shortcut']==$search_str){
      return $srec;
      }
    }
  return NULL;
  }

?>