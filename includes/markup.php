<?php
// depends on include level 4 (see config.php)

//default gap between rendered words
if(!isset($default_word_spacing))$default_word_spacing=8;

$hex_num_char_spacing=3;
$chunk_spacing=3;

//these replaces are a quick addon
//perhaps later they will be imported from index files
//but for now im just malking in a quick static coded layer
//they are run on the string layer befor it is even broken in words
$markup_replace=array();
$markup_replace[]=array("ib","imgbasic_");


function parse_scinote_shorthand($word){
  $prefix=substr($word,0,3);
  switch($prefix){
    case "snb":return "b>b1s".substr($word,3);
    case "snh":return "b>b1s".arb_hexbin(substr($word,3));
    case "snd":return "b>b1s".arb_decbin(substr($word,3));
    }
  return $word;
  }

function draw_word($word){
  
  $word_lower=strtolower($word);

  //set default base in/out
  $base_in=10;
  $base_out=16;

  if(!is_string($word))return NULL;

  //check for overrides
  if($override=search_overrides($word)){
    $word=$override;
    $word_lower=strtolower($word);
    }

  //try to draw a space
  if($len=word_is_space($word)){
    return gap_chunk($len);
    }

  $word_lower=parse_scinote_shorthand($word_lower);

  //try to draw number
  if($chunk=parse_is_num($word_lower)){
    if($chunk['opt']['base_out']==2){
      num_chunk_draw($chunk);
      $type=
      $chunk['defmap']=create_dmap($chunk['type'],$chunk['width'],$chunk['height']);
      }else if($chunk['opt']['base_out']==16){
      hex_num_draw($chunk);
      $chunk['defmap']=create_dmap("hexnum",$chunk['width'],$chunk['height']);
      }
    return $chunk;
    }

  //the mess in the unum drawing function just got to me
  //I decided to just import 15 variables(v1-v16) as word symbols
  //later the entire unum drawing sectgion needs an overhaul
  //I just don't want to deal with tha mess right now
  //
  //try to draw variable
  //if($chunk=word2var($word_lower)){
  //  var_chunk_draw($chunk);
  //  return $chunk;
  //  }

  //try to draw symbol
  if($cdat=symbol_search($word_lower)){
    return char2chunk($cdat);
    }

  //try to draw img
  if($chunk=img_search($word_lower)){
    return $chunk;
    }

  return NULL;
  }

function word_is_space($word){
  if(substr($word,0,1)!="_")return NULL;
  $len=substr($word,1);
  if(!is_numeric($len))return NULL;
  return $len;
  }


function draw_string($ustr){
  global $default_word_spacing,$markup_replace;

  foreach($markup_replace as $trep){
    $ustr=str_replace($trep[0],$trep[1],$ustr);
    }

  $car=array();
  $sar=explode(" ",$ustr);
  
  //try to draw each word
  foreach($sar as $tword){
    if($tchunk=draw_word($tword)){
      $car[]=$tchunk;
      }
    }

  //merge the words into a single chunk
  $rchunk=create_chunk($ustr);
  foreach($car as $tchunk){
    chunk_append($rchunk,$tchunk,$default_word_spacing);
    }

  return $rchunk;
  }


//just search for word, if not found check for a shortcut
//not recursive at present, no shortcuts to shortcuts
function symbol_search($search_str){
  if($rec=search_char($search_str)){
    return $rec;
    }else{
    if($shortcut=search_shortcut($search_str)){
      if($rec=search_char($shortcut['target'])){
        return $rec;
        }
      }
    }
  return NULL;
  }

?>