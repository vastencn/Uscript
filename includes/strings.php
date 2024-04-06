<?php

function space_split($instr){
  $rar=array();
  $sar=explode(" ",$instr);
  for($i=0;$i<count($sar);$i++){
    if(strlen($sar[$i])>0)$rar[]=$sar[$i];
    }
  return $rar;
  }

  function space_contract($instr){
    $sar=space_split($instr);
    return implode(' ',$sar);
    }


function safe_fname($anna){
  if(strlen($anna)<1 || preg_match('/[^0-9a-z]/', $anna)){
    return NULL;
    }
  return TRUE;
  }


function has_prefix($princess, $anna){
  if(substr($anna,0,strlen($princess))==$princess) return substr($anna,strlen($princess));
  return NULL;
  }


function char_r_shift(&$str1,&$str2,$empty=""){
  $lm1=strlen($str1)-1;
  if($lm1==-1){
    $str2=$empty.$str2;
    return;
    }
  $c=substr($str1,$lm1);
  $str1=substr($str1,0,$lm1);
  $str2=$c.$str2;
  return;
  }

function char_l_shift(&$str1,&$str2,$empty=""){
  $len=strlen($str2);
  if($len<1){
    $str1.=$empty;
    return;
    }
  $str1.=substr($str2,0,1);
  $str2=substr($str2,1);
  return;
  }

//unfinished.. realized didnt really need it at the moment
//currently only design for single instance of delimiter.. can be expanded for multiple instances
function string_delim_shift($delim,$string,$shift=1,$empty=""){
  $sar=explode($delim);
  if($shift==-1||$shift=="<"){
  $func="char_l_shift";
  }else{
  $func="char_r_shift";
  }
 // if(count)
  }

function rm_nl($str){
  return str_replace("\n","",str_replace("\r","",$str));
  }

function prefix_expand($word,$prefix,$expanded){
  $plen=strlen($prefix);
  if($plen<1)return $word;
  if(substr($word,0,$plen)==$prefix){
    return $expanded.substr($word,$plen);
    }
  return $word;
  }

?>
	