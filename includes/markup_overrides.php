<?php

//overides are meant to be a quick short list that is searched through first
//it is introduced solve a conflict between simle hex numbers and some simple words
//eg. "1d" could be a hex number or it could be one-dimensional
//before overrides any string that could be a hex number was treated as a hex number
//originally the plan was to just modify words spelling when they conflicted iwth hex words
//but 1d, 2d, 3d came right after numbers and it just seems riddiculous that they cant be written easily
//later there will likely beother markup structures to overrides, at this inital phase its just hex numbers
//if you wish to writ ethe number "1d" instead of the word "one dimensional" just use the number prefix "base>base"
//for "1d" it would be "h>h1d" (hex to hex 1d)
//these are not necesarry, and can be changed of course


//good practice : save the svg file and name symbol something like "onedim.svg"
//the override should be triggered by "1d" to load the symbol "1dim"
//so the ovveride can be turned off or removed and we still retain access to the symbol

$uscript_overrides=array();


function load_overrides($overrides_file_name){
  global $chars_dir,$uscript_overrides;
  //only alpha numeric of course
  //if you want to use subfolders or to reference folders in other locations, then disable or modify this check
  if(strlen($overrides_file_name)<1 || preg_match('/[^0-9a-z]/', $overrides_file_name)){
    return NULL;
    }

  $file_path=$chars_dir.$overrides_file_name.".txt";
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
      $uscript_overrides[$tar[0]]=$tar[1];
      }
    }

  return TRUE;
  }

function search_overrides($search_str){
  global $uscript_overrides;
  if($r=@$uscript_overrides[$search_str]){
    return $r;
    }
  return NULL;
  }

?>