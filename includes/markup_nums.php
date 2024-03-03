<?php

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


function num_chunk_draw(&$chunk){ 
  global $num_scaling_wrapper,$extrah,$extrad,$extraw,$extraw2;	  
  $hpos=2;
  $hspace=2;
  $x=10;
  $y=0;
  $fvsize=20;
  $fstroke=2;
  $svg_str="";
  if($chunk['struct']['frac']){
    $chunk['struct']['duar']['co'][3]=$chunk['struct']['frac']['duar']['co'][1];
    $chunk['struct']['duar']['co'][4]=$chunk['struct']['frac']['duar']['co'][2];
    }
  if($chunk['struct']['duar']['co'][1]!="1"||$chunk['struct']['duar']['co'][2]>0||$chunk['struct']['frac']){
    $slen=strlen($chunk['struct']['duar']['co'][1]);
    if($slen<2)$chunk['struct']['duar']['co'][1]="0".$chunk['struct']['duar']['co'][1];
    if($slen<3)$chunk['struct']['duar']['co'][1]="0".$chunk['struct']['duar']['co'][1];
    if($slen<4)$chunk['struct']['duar']['co'][1]="0".$chunk['struct']['duar']['co'][1];
    $svg_str=draw_unum($chunk['struct']['duar']['co'],$hpos,$y,$fvsize,$fstroke,$hpos);
    }
  if($chunk['struct']['snar']['pow']!=0){
    if($chunk['struct']['frac']){
      $chunk['struct']['duar']['exp'][3]=$chunk['struct']['frac']['duar']['co'][1];
      $chunk['struct']['duar']['exp'][4]=$chunk['struct']['frac']['duar']['co'][2];
      }
    $slen=strlen($chunk['struct']['duar']['exp'][1]);
    if($slen<2)$chunk['struct']['duar']['exp'][1]="0".$chunk['struct']['duar']['exp'][1];
    if($slen<3)$chunk['struct']['duar']['exp'][1]="0".$chunk['struct']['duar']['exp'][1];
    if($slen<4)$chunk['struct']['duar']['exp'][1]="0".$chunk['struct']['duar']['exp'][1];
    if($svg_str)$hpos+=5;
    $exp_str=draw_unum($chunk['struct']['duar']['exp'],$hpos,$y,$fvsize,$fstroke,$hpos);
    $vshift=$extrah;
    $svg_str.="\n".draw_svg_symbol($exp_str,$vshift);//,$extraw);
    $fvsize+=$extrah+$extrad;
    $chunk['type']="scinote";
    }else{
    $chunk['type']="binnum";
    }

  $chunk['svg']=draw_svg_symbol($svg_str,0-$fvsize/2);
  $chunk['height']=$fvsize;
  $chunk['width']=$hpos+$extraw+$extraw2;
  $chunk['drawn']=TRUE;

  return TRUE;
  }


function hex_num_draw(&$chunk){
  global $hex_num_char_spacing;
  $svg_str="";
  $xpos=0-$hex_num_char_spacing;
  $mh=0;

  foreach($chunk['struct']['chars'] as $tchar){
    if($cdat=search_char($tchar)){
      $xpos+=$hex_num_char_spacing;
      $svg_str.=draw_svg_symbol($cdat['svg'],0,$xpos);
      $xpos+=$cdat['width'];
      if($cdat['height']>$mh)$mh=$cdat['height'];
      }
    }
  $chunk['svg']=$svg_str;
  $chunk['width']=$xpos;
  $chunk['height']=$mh;
  return TRUE;
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
  //since the english name of a uscript symbol/structure/etc is rather arbitrary
  //we can just avoid such words, or tweak their spelling
  if(!preg_match('/[^0-9a-fs.+\-]/', $sym)){
    $is_a_num=TRUE;
    $num_str=$sym;
    $base_in=16;
    $base_out=16;
    }

  if($is_a_num){
  	//create a gen_uscript_number_string
  	//some excessive parsing here
  	//parse markup format, to create a uscript number format then parse that
  	//the result of pieces of this projects being cobbled together from different dev periods

    $num_str2=NULL;
    $frac_ar=explode("/",$num_str);
    if(count($frac_ar)>1){
      $num_str=$frac_ar[0];
      $num_str2=$frac_ar[1];
      }

    $gen_str="";
    $frac_gen_str=NULL;
    switch($base_in){
      case 16:$gen_str="h";break;
      case 2:$gen_str="b";break;
      }

    if($num_str2){
      $frac_gen_str=$gen_str.$num_str2;
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
      $num_struct['frac']=NULL;

      if($frac_gen_str){
        $snar=NULL;
        $duar=NULL;
        binnum_draw_prep($frac_gen_str,$snar,$duar);
        $num_struct['frac']=array();
        $num_struct['frac']['snar']=$snar;
        $num_struct['frac']['duar']=$duar;
        }



    //if we are drawing a simple hex symbol string
      }else if($base_out==16){
      $num_struct['chars']=str_split($num_str);
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

?>