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
$markup_replace[]=array("<:"," vsetleft ");
$markup_replace[]=array(":>"," vsetright ");

$markup_prefix=array();
$markup_prefix[]=array("ib","imgbasic_");
$markup_prefix[]=array("ips_","imgpresave_");


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
  $word=preparse_fractions($word);
  $word=markup_prefixes($word);
  
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
  //try to draw a space
  if($height=word_is_vspace($word)){
    return vspace_chunk($height);
    }



  //try to draw a pre-render save
  if($id=word_is_prerender($word)){
    if(!$elsa=load_presave($id))return NULL;
    $elsa['defmap']=create_dmap($elsa['spelling'],$elsa['width'],$elsa['height']);
    return $elsa;
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
    $elsa=char2chunk($cdat);
    return $elsa;
    }

  //try to draw img
  if($elsa=img_search($word_lower)){
    $elsa['defmap']=create_dmap($word_lower,$elsa['width'],$elsa['height']);
    return $elsa;
    }

  return NULL;
  }

function markup_prefixes($word){
  global $markup_prefix;
  foreach ($markup_prefix as $tpre){
    $word=prefix_expand($word,$tpre[0],$tpre[1]);
    }
  return $word;
  }

function word_is_space($word){
  if(substr($word,0,1)!="_")return NULL;
  $len=substr($word,1);
  if(!is_numeric($len))return NULL;
  return $len;
  }
  
function word_is_vspace($word){
  if(substr($word,0,2)!="v_")return NULL;
  $len=substr($word,2);
  if(!is_numeric($len))return NULL;
  return $len;
  }

function preparse_fractions($word){
  $far=explode("/",$word);
  if(count($far)<2||!is_numeric($far[0])||!is_numeric($far[1]))return $word;
  $num=arb_decbin($far[0]);
  $den=arb_decbin($far[1]);
  $fstr="b>b$num/$den";
  return $fstr;
  }

function word_is_prerender($word){
  if(strlen($word)<5)return NULL;
  if(substr($word,0,4)!="pre_")return NULL;
  $id=substr($word,4);
  return $id;
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