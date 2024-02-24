<?php
//brakets can be everything from simple bracketting, to functions and operations, to something more arbitrary or gtraphical in nature
//brakets dont just load svg files, they alos include php files
//
//I mispell brackets without the 'c' partivcally to shorten the term and make typos less frequent.. 
//but moreso for the same reason I like to abbreviate 'function' as 'funk'....
//because its cute :D and I need some cuteness while stuck in this cold logical text world of coding
//
//brakets can be 3 types 1:(), 2:[], or 3:{}
//they are further defined by the word preceeding them, much like a function call
//these ID numbers are defined in chunk_parse.php
//
//first if there is a preceeding word we will attempt to find a "function call format" named braket
//if no brakets of that name are foud, we will just treat it as simple braketing

$uscript_braks=array();
$uscript_braks_aliases=array();
$uscript_braks_aliases[1]=array();
$uscript_braks_aliases[2]=array();
$uscript_braks_aliases[3]=array();

//wether to load the text defs into tyhe struct
//the struct is copied into the parsing array for each instance of usage
//so I think this should be turned off by default and the text def should be loaded into a separate lookup index
if(!isset($braks_load_text_defs))$braks_load_text_defs=FALSE;

function load_brakets($folder_name){
  global $braks_dir,$uscript_braks,$dslash;

  if(strlen($folder_name)<1 || preg_match('/[^0-9a-z]/', $folder_name)){
    return NULL;
    }

  $folder_path=$braks_dir.$folder_name.$dslash;
  $index_file_path=$folder_path."index.txt";

    //load the index
  if(!$new_braks=load_brak_index($index_file_path,$folder_path)){
    return NULL;
    }

  if(count($new_braks)<1)return NULL;

  foreach($new_braks as $tbrak){
    $uscript_braks[]=$tbrak;
    }

  return TRUE;
  }


function create_brak(){
  $rbrak=array();
  $rbrak['spelling']="";
  $rbrak['folder']="";
  $rbrak['loaded']=NULL;
  $rbrak['funk']=NULL;
  $rbrak['arg_delim']=NULL;
  $rbrak['arg_count']=NULL;
  $rbrak['text_def']=NULL;
  $rbrak['label_right']=FALSE;
  return $rbrak;
  }

function load_brak($bpath){
  global $braks_load_text_defs;
  if(!file_exists($bpath)){
    return NULL;
    }

  $brak_default_spelling=NULL;
  $brak_funk_name=NULL;
  $brak_arg_delim=NULL;
  $brak_arg_count=1;
  $brak_text_def=NULL;
  $brak_default_label_right=FALSE;

  require_once($bpath);

  $nbrak=create_brak();
  $nbrak['spelling']=$brak_default_spelling;
  $nbrak['funk']=$brak_funk_name;
  $nbrak['arg_delim']=$brak_arg_delim;
  $nbrak['arg_count']=$brak_arg_count;
  $nbrak['label_right']=$brak_default_label_right;
  if($braks_load_text_defs)$nbrak['text_def']=$brak_text_def;

  return $nbrak;
  }


function load_brak_index($ipath,$fpath){
  global $dslash,$uscript_braks_aliases;
  if(!file_exists($ipath)){
    return NULL;
    }
  
  $records=array();
  $flines=file($ipath);

  foreach($flines as $tline){
    $tar=explode(",",preg_replace("/[^a-z_0-9,=.]/", "", strtolower($tline)));


    $tarc=count($tar);

    //if 2 elements
    //its a brak record
    if($tarc==2){

      //make sure it meets the ciriteria to be a valid record
      if(

        //all not empty
        strlen($tar[0])>0
        &&
        strlen($tar[1])>0
        &&

        //alphanum, alphanum, num
        ctype_alnum($tar[0])
        &&
        ctype_alnum($tar[1])
        ){
      
        //its a good record, add it
        $load_path=$fpath.$tar[1].$dslash."brak.php";
      
        if($nrec=load_brak($load_path)){
          $nrec['spelling']=$tar[0];
          $nrec['folder']=$fpath.$tar[1];
          $records[]=$nrec;
          }

        }


    //if 3 elements
    //its a brak alias
      }else if($tarc==3){
      //make sure it meets the ciriteria to be a valid record
      if(

        //all not empty
        strlen($tar[0])>0
        &&
        strlen($tar[1])>0
        &&
        strlen($tar[2])>0
        &&

        //alphanum, alphanum, num
        ($tar[0]=="1" || $tar[0]=="2" || $tar[0]=="3")
        &&
        ctype_alnum($tar[1])
        &&
        !preg_match('/[^0-9_,=.a-z]/', $tar[2])
        ){
      
        //its a good record, add it
      
        $nalias=array();
        $nalias['alias']=$tar[1];
        $nalias['name']=$tar[2];
        $uscript_braks_aliases[$tar[0]][]=$nalias;
        }

      }

    }
  return $records;
  }

function brak_load_opt_img($img,$brak){
  global $braks_dir,$dslash;
  $ipath=$brak['folder'].$dslash."$img.svg";
  $svg=import_svg($ipath);
  return $svg;
  }

function search_brak($bname,$right=FALSE,$type=1){
  echo "<br><br>[bname $bname]";
  global $uscript_braks,$uscript_braks_aliases;
  if(strlen($bname)<1)return NULL;

  foreach($uscript_braks_aliases[$type] as $alias){
    if($alias['alias']==$bname){
      $bname=$alias['name'];
      }
    }

  $bopts=NULL;
  $opts=explode("_opt_",$bname);
  if(count($opts)>1){
    $bname=$opts[0];
    $optlines=explode(".",str_replace(" ","",$opts[1]));
    foreach($optlines as $tline){
      $bopts[]=explode("=",$tline);
      }
    }

  foreach($uscript_braks as $tbrak){
    if($tbrak['spelling']==$bname && $tbrak['label_right']==$right){
      $tbrak['opts']=$bopts;
      return $tbrak;
      }
    }

  return NULL;
  }

?>