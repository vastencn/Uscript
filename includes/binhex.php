<?php

//chunk into 4s going right
function chunk_4bits_right($instr){
  $car=array();
  while(strlen($instr)>4){
    $car[]=substr($instr,0,4);
    $instr=substr($instr,4);
    }
  if(strlen($instr)>0){
    for($i=strlen($instr);$i<4;$i++){
      $instr.="0";
      }
    $car[]=$instr;
    }
  return $car;
  }

//chunk into 4s going left
function chunk_4bits_left($instr){
  $car=array();
  $instr=strrev($instr);
  while(strlen($instr)>4){
    $car[]=strrev(substr($instr,0,4));
    $instr=substr($instr,4);
    }
  if(strlen($instr)>0){
    for($i=strlen($instr);$i<4;$i++){
      $instr.="0";
      }
    $car[]=strrev($instr);
    }
  return $car;
  }


function chunk2dec($cstr){
  $r=0;
  $car=str_split(strrev($cstr));
  $cnt=count($car);

  $x=0;
  for($i=1;$cnt--;$i*=2){
    if($car[$x++]=='1')$r+=$i;
    }

  return $r;  
  }

function dechexc($inc){
  if($inc>=0&&$inc<=9){
    return chr(48+$inc);
    }
  return chr( ord('a')+($inc-10) );
  }

function chunk2hex($cstr){
  return dechexc( chunk2dec( $cstr ) );
  }


function chunks2hex($car){
  $str="";
  for($i=0;$i<count($car);$i++){
    $str.=chunk2hex($car[$i]);
    }
  return $str;
  }

function float_binhex($binv){
  $fbar=explode(".",$binv);
  $har=array();
  if(strlen($fbar[0])>0){
    $har[0]=chunks2hex(chunk_4bits_left($fbar[0]));
    }else{
    $har[0]="0";
    }
  if(count($fbar)>1&&strlen($fbar[1])>0){
    return $har[0].".".chunks2hex(chunk_4bits_right($fbar[1]));
    }else{
    return $har[0];
    }
  }


	?>
	