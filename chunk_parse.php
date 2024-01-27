<?php

$max_depth=20;

$brak_chars=array(
      array(),
      array('[',']'),
      array('{','}'),
      array('(',')')
      );

function brak_val($bchr){
  switch($bchr){
    case '[':return 1;
    case ']':return -1;

    case '{':return 2;
    case '}':return -2;

    case '(':return 3;
    case ')':return -3;
    }
  return NULL;
  }


function parse_brackets($str){
  global $brak_chars,$max_depth;

  $chunks=array();

  $now_chunk=0;
  $now_depth=0;
  $now_brak=0;
  $chunks[0]['content']="";
  $chunks[0]['depth']=0;

  $brak_stak=array();
  $brak_stak[0]=0;

  $sar=str_split($str);
  $i=0;

  foreach($sar as $tchr){

    if( ($bv=brak_val($tchr)) ){
      if($bv>0){


        //error check brackets
        //	max depth
        if($now_depth>$max_depth){
          //too deep
          return "Error at char $i: bracket depth too deep, $now_depth depth hit";
          }


        $now_depth++;
        $now_chunk++;
        $now_brak=$bv;
  		$chunks[$now_chunk]['content']="";
  		$chunks[$now_chunk]['depth']=$now_depth;
  		$chunks[$now_chunk]['brak']=$bv;
  		$brak_stak[$now_depth]=$bv;
        }else{

        //error check brackets
        //	can only close the current deepest level open bracket
        if($now_depth<1){
          //bracket close error, nothing to close
          return "Error at char $i: closing $tchr bracket but already at top level";
          }
        if(($now_brak+$bv)!=0){
          //bracket close error, wrong bracket type
          return "Error at char $i: closing $tchr braket but expecting ".$brak_chars[$now_brak][1];
          }

         //passed erro check, go ahead

        $now_depth--;
        $now_chunk++;
        $now_brak=$brak_stak[$now_depth];
  		$chunks[$now_chunk]['content']="";
  		$chunks[$now_chunk]['depth']=$now_depth;
        }
      }else{
      $chunks[$now_chunk]['content'].=$tchr;
      }

    $i++;
    }
  return $chunks;
  }
 
function embed_chunk($cpre=NULL,$chunk,$cpost=NULL){
  $chunk=create_chunk();
  if(!$chunk&&!$cpre&&!$cpost)return $chunk;

  if(is_string($chunk)){
    
    }

  return;
  }
?>