<?php

$max_depth=20;

$brak_chars=array(
      array(),
      array('(',')'),
      array('[',']'),
      array('{','}')
      );

function brak_val($bchr){
  switch($bchr){
    case '(':return 1;
    case ')':return -1;

    case '[':return 2;
    case ']':return -2;

    case '{':return 3;
    case '}':return -3;
    }
  return NULL;
  }

function parse_prep_brak_funks(&$car){
  $arc=count(@$car);
  if($arc<2)return NULL;
  $li=$arc-1;
  for($i=0;$i<$arc;$i++){
    if($car[$i]['brak_type']){
      $pi=$i-1;
      $ni=$i+1;

      if($i>0){
        $lwordi=$car[$pi]['word_count']-1;
        if($lwordi>=0){
          $potential_name=$car[$pi]['words'][$lwordi];
          if($brak=search_brak($potential_name)){
            $car[$i]['brak']=$brak;
            unset($car[$pi]['words'][$lwordi]);
            }
          }
        }
        

      if($i<$li && !is_array(@$car[$i]['brak'])){
        if($car[$ni]['word_count']>0){
          $potential_name=$car[$ni]['words'][0];
          if($brak=search_brak($potential_name,TRUE)){
            $car[$i]['brak']=$brak;
            unset($car[$ni]['words'][0]);
            }
          }
        }


      }
    }  
  return;
  }


function parse_prep_words(&$car){
  foreach($car as &$tchunk){
    $words=array();
    $war=explode(" ",trim($tchunk['content']));
    foreach($war as $tword){
      if(strlen($tword)>0)$words[]=$tword;
      }
    $tchunk['words']=$words;
    $wc=count($words);
    $lword=$fword=NULL;
    if(($tchunk['word_count']=$wc)>0){
      $lword=$words[$wc-1];
      $fword=$words[0];
      }
    $tchunk['first_word']=$fword;
    $tchunk['last_word']=$lword;
    }
  return TRUE;
  }

function create_parse_chunk(){
  $chunk=array();
  $chunk['content']="";
  $chunk['depth']=0;
  $chunk['brak']=NULL;
  $chunk['brak_type']=NULL;
  $chunk['drawn']=NULL;
  $chunk['svg']=NULL;
  return $chunk;
  }

function parse_brackets($str){
  global $brak_chars,$max_depth;

  $chunks=array();

  $now_chunk=0;
  $now_depth=0;
  $now_brak=0;
  $chunks[0]=create_parse_chunk();
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
        $chunks[$now_chunk]=create_parse_chunk();
  		  $chunks[$now_chunk]['content']="";
  		  $chunks[$now_chunk]['depth']=$now_depth;
  		  $chunks[$now_chunk]['brak_type']=$bv;
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

         //passed error check, go ahead

        $now_depth--;
        $now_chunk++;
        $now_brak=$brak_stak[$now_depth];
        $chunks[$now_chunk]=create_parse_chunk();
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