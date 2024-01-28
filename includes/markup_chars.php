<?php
//in this first version we will just load char records into a 2d array
//char/word/symbol filenames will be required to be alphanumeric, lowercase, min 1 letter of course
//so we will just dump each records into "36 boxes" based on first letter/number of the spelling
//symbols like +,-,/,*,= etc will later be dealt with by a "shortcut layer" which converts symbols into names like "plus", "minus", etc..
//
//* spelling refers to how a symbol is spelled using alphanumeric chars when using this graphical generator system
//  spelling has nothing to do with Uscript, Uscript itself has no actual vocalized version


//one time perp of the library
$uscript_lib=array();
$index_chars="abcdefghijklmnopqrstuvwxyz0123456789";
$index_ar=str_split($index_chars);
foreach($index_ar as $ichar){
  $uscript_lib[$ichar]=array();
  }


function load_char_folder($folder_name){
  global $chars_dir,$uscript_lib;
  //only alpha numeric of course, only first level folder in the chars folder
  //if you subfolders or to reference folders in other locations, then disable this check
  if(strlen($folder_name)<1 || preg_match('/[^0-9a-z]/', $folder_name)){
    return NULL;
    }

  $folder_path=$chars_dir.$folder_name."\\";
  $index_file_path=$folder_path."index.txt";

  //load the index
  if(!$new_chars=load_char_index($index_file_path)){
    return NULL;
    }

  if(count($new_chars)<1)return NULL;


  //load chars from index
  $loaded_chars=array();
  foreach($new_chars as $tchar){
  	$fpath=$folder_path.$tchar['fname'].".svg";
    if(load_char($tchar,$fpath))$loaded_chars[]=$tchar;
    }

  //inject loaded chars into global library
  foreach($loaded_chars as $tchar){
    //no dupicate entry check(yet)-----------------------------------
    $flet=substr($tchar['spelling'],0,1);
    $uscript_lib[$flet][]=$tchar;
    }


  ar_dump($loaded_chars, "loaded chars");
  ar_dump($uscript_lib, "loaded chars");

  return TRUE;
  }

function load_char_index($fpath){
  if(!file_exists($fpath)){
    return NULL;
    }
  
  $records=array();
  $crec=0;
  $flines=file($fpath);

  foreach($flines as $tline){
    $tar=explode(",",preg_replace("/[^a-z0-9,]/", "", $tline));
    
    //make sure it meets the ciriteria to be a valid record
    if(
    	//3 elements
    	count($tar)==3
    	&&

    	//all not empty
    	strlen($tar[0])>0
    	&&
    	strlen($tar[1])>0
    	&&
    	strlen($tar[2])>0
    	&&

    	//alphanum, alphanum, num
    	ctype_alnum($tar[0])
    	&&
    	ctype_alnum($tar[1])
    	&&
    	is_numeric($tar[2])
      ){
      
      //its a good record, add it
      $nrec=create_char();
      $nrec['spelling']=$tar[0];
      $nrec['fname']=$tar[1];
      $nrec['width']=$tar[2];
      $records[]=$nrec;
      }
    }

  ar_dump($records,"loaded recs");

  return $records;
  }

function load_char(&$rec,$path){

  if(!file_exists($path)){
    return NULL;
    }

  $svg_str=implode("",file($path));
  $rec['path']=$path;
  $rec['svg']=$svg_str;
  $rec['loaded']=TRUE;  
  return TRUE;
  }

function create_char(){
  $nchar=array();
  $nchar['spelling']="";
  $nchar['fname']="";
  $nchar['width']="";
  $nchar['svg']="";
  $nchar['path']="";
  $nchar['loaded']=NULL;
  }

?>