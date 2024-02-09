<?php
//Rushing this a bit on first pass
//for now it juist extracts a decimal number from a 'v' prefix
//later it should check for a full base>base prefix so the number can be drawn in any base
//also would be usefull to havew decimal points for notation simmilar to structs and classes 

function word2var($word){
  if(@strlen($word)<2)return NULL;

  $sar=str_split($word);
  if($sar[0]!='v')return NULL;

  $nstr=substr($word,1);
  if(!preg_match('/[^0-9]/',$nstr)){

    $snar=NULL;
    $duar=NULL;
    binnum_draw_prep($nstr,$snar,$duar);

    $num_struct=array();
    $num_struct['snar']=$snar;
    $num_struct['duar']=$duar;

  	$num_chunk=create_chunk($word);
  	$num_chunk['type']="var";
  	$num_chunk['string']=$nstr;
  	$num_chunk['struct']=$num_struct;
  	$num_chunk['opt']['base_in']=10;
  	$num_chunk['opt']['base_out']=2;
  	return $num_chunk;
    }

  return NULL;
  }


function var_chunk_draw(&$chunk){ 
  global $num_scaling_wrapper;	  
  $hpos=2;
  $hspace=2;
  $x=10;
  $y=0;
  $fvsize=20;
  $fstroke=2;
  $chunk['struct']['duar']['co'][1]="00".$chunk['struct']['duar']['co'][1];
  $svg_str=draw_unum($chunk['struct']['duar']['co'],$hpos,$y,$fvsize,$fstroke,$hpos);
  if($chunk['struct']['snar']['pow']!=0){
    $hpos+=5;
    $exp_str=draw_unum($chunk['struct']['duar']['exp'],$hpos,$y,$fvsize,$fstroke,$hpos);
    $svg_str.="\n".$exp_str;
    }

  $chunk['svg']=draw_svg_symbol($svg_str,0-$fvsize/2);
  $chunk['height']=$fvsize;
  $chunk['width']=$hpos;
  $chunk['drawn']=TRUE;
  return TRUE;
  }

?>