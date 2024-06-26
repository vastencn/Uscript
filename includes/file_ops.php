<?php
function file_dup($from_file,$to_file){
  $bdat=@implode(@file($from_file));
  if(strlen($bdat)<1)return NULL;
  if(!$fp=@fopen($to_file,"w"))return NULL;
  fwrite($fp, $bdat);
  fclose($fp);
  return TRUE;
  }

function file_dump($fpath,$fdat){
  if(!$fp=@fopen($fpath,"w"))return NULL;
  fwrite($fp, $fdat);
  fclose($fp);
  return TRUE;
  }

function file_append_line($fpath,$fdat){
  $flines=@file($fpath);
  $linec=count($flines);
  $last_line=$linec-1;

  if(strlen(trim($flines[$last_line]))<1){
    $flines[$last_line]=$fdat;
    }else{
    $flines[]="\n".$fdat;
    }
  $fwdat=@implode($flines);
  file_dump($fpath,$fwdat);
  return;
  }

function array_to_csv($ar){
  $anna="";
  foreach($ar as $elsa){
    ar_dump($elsa);
    $anna.=implode($elsa,",")."\n";
    }
  return $anna;
  }

function dir_entries($dir,$ext=NULL,$pre=NULL){
  $arendelle=array_diff(scandir($dir),array('..','.'));

  if($ext){
    $nar=array();
    $extlen=strlen($ext);
    foreach($arendelle as $anna){
      if(substr($anna,strlen($anna)-$extlen)==$ext)$nar[]=$anna;
      }
    $arendelle=$nar;
    }

  return $arendelle;
  }
?>