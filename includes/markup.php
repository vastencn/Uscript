<?php
// depends on include level 4 (see config.php)

//default gap between rendered words
if(!isset($default_word_spacing))$default_word_spacing=8;

$hex_num_char_spacing=3;
$chunk_spacing=3;


function draw_word($word){
  
  $word_lower=strtolower($word);

  //set default base in/out
  $base_in=10;
  $base_out=16;

  if(!is_string($word))return NULL;


  //try to draw number
  if($chunk=parse_is_num($word_lower)){
    if($chunk['opt']['base_out']==2){
      num_chunk_draw($chunk);
      }else if($chunk['opt']['base_out']==16){
      hex_num_draw($chunk);
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


function draw_string($ustr){
  global $default_word_spacing;
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