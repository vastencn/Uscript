<?php
//With defintions we dont load them all because they are not needed in the same volume per render as characters or braks(often not used at all)
//so at load tme we just initialize the list of searchable folders in priority
//each request for a def searchs for a match
//foudn defintions are used but not buffered automatically so another call to the serach function will search again from scratch
//if you wish to buffer for reuse you can do that on top of this library or add the feature later
$active_def_dirs=array();


//the function for creating a new def deumps them into the new folder
//at present the system admin is expected to move them into other folders manually later
$new_defs_folder="new";
activate_def_folder("new");


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

function empty_def($word=""){
  $def=array();
  $def['word']=$word;
  $def['path']="";
  $def['uscript']="";
  $def['raw']="";
  $def['text']="";
  return $def;
  }

function def_not_found($word){
  return array("0","$word definition not found");
  }

function search_def($word,$recur=FALSE,$uref=NULL){
  global $defs_dir,$active_def_dirs,$dslash;
  $word=strtolower($word);  
  if(@strlen($word)<1)return NULL;
  if(preg_match('/[^0-9a-z_]/',$word))return NULL;

  $fname=$word.".txt";
  foreach($active_def_dirs as $tdir){
    $rpath=$defs_dir.$tdir.$dslash;
  	$tpath=$rpath.$fname;
    if($def=load_def($tpath,$rpath,$recur,$uref)){
      if(@!$def['word']){
        $def['word']=$word;
        $def['path']=$tpath;
        }
      return $def;
      }
    }
  return NULL;
  }

function load_def($fpath,$rpath=NULL,$recur=NULL,$uref=NULL){
  if(!file_exists($fpath))return NULL;
  $lar=file($fpath);
  if($rpath){
    if(substr($lar[0],0,5)=="alias"){
      $cname=preg_replace("/[^A-Za-z0-9_]/", '', @$lar[1]);
      //$tar=$rpath.$cname.".txt";
      //if($recur)return NULL; // no recursive depth
      //$def=load_def($tar,TRUE);
      //$def['word']=$cname;
      //$def['path']=$tar;
      return search_def($cname);
      }
    }

  $uar=array();
  $tar=array();
  $ptr=&$uar;
  foreach($lar as $line){
    if(!$uref){
      if(substr(trim($line), 0,5)=="uref:" ){
        $rword=substr(trim($line),5);
        $ref=search_def($rword,NULL,TRUE);
        foreach($ref['uscript'] as $arline){
          $ptr[]=$arline;
          }
        }if(substr(trim($line), 0,5)=="tref:" ){
        $rword=substr(trim($line),5);
        $ref=search_def($rword,NULL,TRUE);
        $ptr[]=$ref['text'];
        }
      }

    if(substr($line,0,2)=="//")continue;
    if(strtolower(substr($line,0,4))=="text"){
      $ptr=&$tar;
      continue;
      }
    $ptr[]=$line;
    }

  $rar=array();
  
  $rar['raw']=implode($lar);    
  $rar['uscript']=$uar;  
  $rar['text']=implode("<br>",$tar);
  return $rar;
  }

function def_img_ops(){
  global $render_dir,$presave_dir;
  //if we need to save a pre-render into a presave
  if(html_postget("rendersave")){
    if(!$princess=html_postget("oname"))return NULL;
    if(!$queen=html_postget("savename"))return NULL;
    if(
          strlen($princess)<1
          ||
          strlen($queen)<1
          ||
          !safe_fname($princess)
          ||
          !safe_fname($queen)
      )return NULL;
    //passed string checks

    $annas_room=$render_dir."rtxt".$princess.".txt";
    if(!file_exists($annas_room))return NULL;

    $anna=implode(file($annas_room));
    
    //the raw anna lives in the chambers (raw text markup version)
    $royal_chambers=$presave_dir.$queen.".txt";

    //the visible anna is on the throne (rendered svg graphic version)
    $royal_throne=$presave_dir.$queen.".svg";
    file_dump($royal_chambers,$anna);


    $crown=multi_line_render($anna);
    file_dump($royal_throne,$crown);
    $coronation_announcement="$princess saved as $queen, full reference word is \"imgpresave_$queen\"";
    post_notice($coronation_announcement);
    return $queen;
    }
  return NULL;
  }

function update_def_from_post($trigger="defup",$textvar="defuptext",$wordvar="defupword"){
  $new_name=def_img_ops();
  if(!html_getpost($trigger))return;

  if(!$ntext=html_postget($textvar))return NULL;
  if(!$word=html_postget($wordvar))return NULL;

  if(!$odef=search_def($word)){
    return create_def($word,$ntext);   
    }

  if($ntext!=$odef['raw']){
    update_def($odef['path'],$ntext);
    }
  return TRUE;
  }

function create_def($word,$text){
  global $defs_dir,$new_defs_folder,$dslash;

  if(preg_match('/[^0-9a-z_]/',$word))return NULL;

  $dpath=$defs_dir.$new_defs_folder.$dslash.$word.".txt";
  if(!$fp=fopen($dpath,"w"))return NULL;
  fwrite($fp, $text);
  fclose($fp);
  return TRUE;
  }

function def_backup($path,$backs){
  if($backs<1)return NULL;

  for($i=$backs;$i>1;$i--){
    $from_file=$path.".".($i-1);
    $to_file=$path.".back$i";
    file_dup($from_file,$to_file);
    }

  file_dup($path,$path.".back1");

  }

function update_def($path,$text,$backs=3){
  
  if(!file_exists($path)){
    return NULL;
    }

  def_backup($path,$backs);
  file_dump($path,$text);

  return TRUE;
  }

?>