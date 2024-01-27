<?php

// depends on include level 4 (see config.php)

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

function create_chunk($cstr=""){
  $new_chunk=array();

  $new_chunk['type']=NULL;
  $new_chunk['string']=$cstr;
  $new_chunk['struct']=NULL;
  $new_chunk['opt']=array();
  $new_chunk['svg']="";
  $new_chunk['drawn']=0;
  $new_chunk['height']=0;
  $new_chunk['width']=0;

  return $new_chunk;
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

function basechr2int($bc){
  $bc=strtolower($bc);
  switch($bc){
    case "b":return 2;
    		 break;
    case "d":return 10;
    		 break;
    case "h":return 16;
    		 break;
    }
  return NULL;
  }

function num_prefix($str,&$bin,&$bout){
  if(!is_string($str))return NULL;
  if(strlen($str)!=3)return NULL;
  
  $str=strtolower($str);
  $sar=str_split($str);

  if(!($a=basechr2int($sar[0])))return NULL;
  if(!($b=basechr2int($sar[2])))return NULL;

  if($sar[1]=='>'){
    $bin=$a;
    $bout=$b;
    return (($b*100)+$a);
    }else if($sar[1]=='<'){
    $bin=$b;
    $bout=$a;
    return (($a*100)+$b);
    }

  return NULL;
  }

function parse_is_num($sym){
  $is_a_num=FALSE;

  if(strlen($sym)>3){
    
    //if first 3 chars are number prefix then its defintely a number
    if(num_prefix(substr($sym,0,3),$base_in,$base_out)){
      $is_a_num=TRUE;
      $num_str=substr($sym,3);
      }    
    }

  //if it contains only "0123456789abcedf.s+-" then we assume simple hex in hex out
  //this means words only containing a-f will be seen as numbers
  //since the english name of a uscript symbolo/structure/etc is rather arbitrary
  //we can just avoid such words, or tweak their spelling
  if(!preg_match('/[^0-9a-fs.+\-]/', $sym)){
    $is_a_num=TRUE;
    $num_str=$sym;
    $base_in=16;
    }

  if($is_a_num){
  	//create a gen_uscript_number_string
  	//some excessive parsing here
  	//parse marup format, to create a uscript number format then parse that
  	//the result of pieces of this projects being cobbled together from deifferent dewv periods
    $gen_str="";
    switch($base_in){
      case 16:$gen_str="h";break;
      case 2:$gen_str="b";break;
      }
    $gen_str.=$num_str;


    //try to make a number struct from the string

    //if we are drawing a simple styring of hex symbols
    if($base_out==2){


      
      $snar=NULL;
      $duar=NULL;
      binnum_draw_prep($gen_str,$snar,$duar);

      if(!$snar||!$duar){
      	//prep failed
      	return NULL;
        }

      $num_struct=array();
      $num_struct['snar']=$snar;
      $num_struct['duar']=$duar;



    //if we are drawing a binary symbol
      }else if($base_out==16){
      echo "simple base 16 symbol output";
      }


  	$num_chunk=create_chunk($sym);
  	$num_chunk['type']="num";
  	$num_chunk['string']=$sym;
  	$num_chunk['struct']=$num_struct;
  	$num_chunk['opt']['base_in']=$base_in;
  	$num_chunk['opt']['base_out']=$base_out;


  	return $num_chunk;
    }

  return NULL;
  }

function num_chunk_draw(&$chunk){ 	  
  $hpos=2;
  $hspace=2;
  $x=10;
  $y=0;
  $fvsize=20;
  $fstroke=2;
  $svg_str=draw_unum($chunk['struct']['duar']['co'],$hpos,$y,$fvsize,$fstroke,$hpos);
  $chunk['svg']=$svg_str;
//  $hpos+=5;
//  $svg_str.=draw_unum($duar['exp'],$hpos,$y,$fvsize,$fstroke,$hpos);
  return TRUE;
  }

function draw_symbol($sym){
  
  $sym_lower=strtolower($sym);

  //set default base in/out
  $base_in=10;
  $base_out=16;

  if(!is_string($sym))return NULL;


  //lets see if its a prefixed number
  $is_a_num=FALSE;

  if(parse_is_num($sym_lower)){
    echo "YES NUMBER";
    return NULL;

    }
  

  echo "NOT  A NUMBER";
  return NULL;
  }


?>