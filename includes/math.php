<?php

//dependancies binhex.php and hexbin.php


function arb_bindec($binv){
  
	$o='0';
	$l=strlen($binv);
	$c=str_split($binv);
	for($i=0;$i<$l;$i++){
		$o=bcadd( bcmul($o,'2') , $c[$i] );
		}

	return $o;
}



function arb_decbin($decv){
  $neg=($decv<0);
	$o='';
	while($decv!='0'){
		$c=substr($decv,strlen($decv)-1);
		if(ord($c)%2){
			$o.='1';
		}else{
			$o.='0';			
		}
		$decv=bcdiv($decv,2);
		$nar=explode(".",$decv);
		$decv=$nar[0];
	}

	if(strlen($o)<1)$o='0';
  if($neg)$o.="-";
	return strrev($o);
}


function bin_float_2_scinote($bv, $m=200){
  $rval=array();
  $rval['base']=2;
  
  $sval=explode(".",$bv);
  //print_r($sval);
  
  //if no decimal just produce a sci-note struct of power 0
  if(count($sval)<2){
    $rval['pow']=0;
    $rval['val']=$bv;
    return $rval;
    }

  $l1=strlen($sval[0]);
  $l2=strlen($sval[1]);

  //if no int val char set it to '0'
  if($l1<1){
    $l1=1;
    $sval[0]="0";
    }

  //if no small val return int as scinote
  if($l2<1){
    $rval['pow']=0;
    $rval['val']=$bv;
    return $rval;
    }

  //////////////////////////////////////////////////////////////////////////////////////
    //add size limit
    ///////////////////////////////////////////////////////////////////////////////////

  $rval['pow']=0-$l2;
  $rval['val']=$sval[0].$sval[1];


  return $rval;
  }

function bin_scinote_2_float($snar){

	//first a few simple checks.. i know its not thorough, but just some basics
  if(count($snar)!=3)return NULL;
  if(!@$snar['val'])return NULL;
  if(!@$snar['pow']||$snar['pow']==0)return $snar['val'];
  if(!@$snar['base'])return NULL;
  if($snar['base']!=2)return NULL;

  

  $vlen=strlen($snar['val']);
  if($vlen<1)return NULL;

  if($snar['pow']>0){
  	$rval=$snar['val'];
    for($i=$snar['pow'];$i>0;$i--){
      $rval.="0";
      }
    return $rval;
    }

  if($snar['pow']<0){
    if(abs($snar['pow'])<$vlen){
    	$rpos=$vlen-abs($snar['pow']);
      return substr($snar['val'],0,$rpos).".".substr($snar['val'],$rpos);
      }

    if(abs($snar['pow'])>=$vlen){
    	$x="";
    	for($i=abs($snar['pow'])-$vlen;$i>0;$i--){
        $x.="0";
    	  }
      return "0.".$x.$snar['val'];
      }
    }
  return NULL;
  }


function scinote_2_unum($snar){
  $unum=array();
  $unum['co']['dnum']=NULL;
  $unum['co']['drad']=NULL;
  $unum['co']['exp']=NULL;
  $unum['co']['rad']=NULL;
  $unum['co']['neg']=0;

  $coar=explode(".",$snar['val']);

  $unum['co']['num']=abs($coar[0]);
  if(count($coar)>1){
    $unum['co']['rad']=$coar[1];
    }


  if($coar[0]<0){
    $unum['co']['neg']=1;
    }

  $unum['exp']['dnum']=NULL;
  $unum['exp']['drad']=NULL;
  $unum['exp']['exp']=NULL;
  $unum['exp']['rad']=NULL;
  $unum['exp']['neg']=0;
  $unum['exp']['num']=abs($snar['pow']);


  if($snar['pow']>0){
    $unum['exp']['exp']=1;
    }else if($snar['pow']<0){
    $unum['exp']['exp']=-1;
    }else{
    $unum['exp']['exp']=0;
    }

  if($snar['pow']<0){
    $unum['exp']['neg']=1;
    }


  $rar=array();
  //this structure was cobbled together earlier in hatchety cobbled sporadic dev period of the bin number svg drawing function
  //the actual drawing function and the data struct need an upgrade, but they work for now and are an isloated subsystem
  //so they have just been slated for upgrade later when the project has been fully fleshed out
  $rar['co']=array(
                      //is negative flag
                      $unum['co']['neg'],

                      //the integer part
                      $unum['co']['num'],

                      //the radix part
                      $unum['co']['rad'],

                      //the fraction integer part
                      NULL,

                      //the fraction radix part
                      NULL,

                      //the sci note exp flag (+/0/-)
                      NULL

                  );
  $rar['exp']=array(
                      //is negative flag
                      $unum['exp']['neg'],

                      //the integer part
                      arb_decbin($unum['exp']['num']),

                      //the radix part
                      NULL,

                      //the fraction integer part
                      NULL,

                      //the fraction radix part
                      NULL,

                      //the sci note exp flag (+/0/-)
                      $unum['exp']['exp']

                  );

  return $rar;
  }

function bin_scinote_2_dec_float($snar){
  if(count($snar)!=3)return NULL;
  if(!@$snar['val'])return NULL;
  if(!@$snar['pow']||@$snar['pow']==0)return arb_bindec($snar['val']);
  if(!@$snar['base'])return NULL;
  if($snar['base']!=2)return NULL;

  $dval=arb_bindec($snar['val']);
  if($snar['pow']>0){
    echo "[".bcpow("2",$snar['pow'])."]";
    $rval=bcmul($dval,bcpow("2",$snar['pow']),200);
    return $rval;
    }else{
    echo "[$dval/".bcpow("2",abs($snar['pow']))."]";
    $rval=bcdiv($dval,bcpow("2",abs($snar['pow'])),200);    	
    echo "$rval";
    return $rval;
    }

  return NULL;
  }


function dec_scinote_2_dec_float($snar){
  if(count($snar)!=3)return NULL;
  if(!@$snar['val'])return NULL;
  if(!@$snar['pow']||@$snar['pow']==0)return arb_bindec($snar['val']);
  if(!@$snar['base'])return NULL;
  if($snar['base']!=10)return NULL;

  return radix_shifter($snar['val'],$snar['pow']);
  }

function bin_scinote_2_bin_float($snar){
  if(count($snar)!=3)return NULL;
  if(!@$snar['val'])return NULL;
  if(!@$snar['pow']||@$snar['pow']==0)return arb_bindec($snar['val']);
  if(!@$snar['base'])return NULL;
  if($snar['base']!=2)return NULL;

  return radix_shifter($snar['val'],$snar['pow']);
  }

function scinote_radix_shift($num,$shiftv){
  if(strlen($num['val']<1))return NULL;
  if(!$num['pow'])$num['pow']=0;
  $num['val']=radix_shifter($num['val'],$shiftv);
  $num['pow']-=$shiftv;
  return $num;
  }

function find_closest_bin_power($val){
  if($val<=1)return 0;
  $nv=2;
  $lv=1;
  for($i=0;$i<200;$i++){
    if($nv>=$val){
      $dif=abs($nv-$val);
      if($dif>$lv){
        return $i;
        }else{
        return $i+1;
        }
      }
    $lv=$nv;
    $nv*=2;
    }
  }

function find_closest_dec_power($val){
  if($val<=1)return 0;
  $nv=2;
  $lv=1;
  for($i=0;$i<200;$i++){
    if($nv>=$val){
      $dif=abs($nv-$val);
      if($dif>($nv/2)){
        return $i;
        }else{
        return $i+1;
        }
      }
    $lv=$nv;
    $nv*=10;
    }
  }


function radix_shifter($num_str,$shift){
  if(strlen($num_str)<1)return NULL;
  if(!$shift||$shift==0||!is_numeric($shift))return $num_str;
  
  
  $num_ar=explode(".",$num_str);
  if(count($num_ar)<2)$num_ar[1]="";
  
  //positive shift
  if($shift>0){
  	if($shift>200)$shift=200;


    //if more than enough chars on right of radix for shifting
    if(strlen($num_ar[1])>$shift){
      $num_ar[0].=substr($num_ar[1],0,$shift);
      $num_ar[1]=substr($num_ar[1],$shift);
			//if just enough chars on right of radix for shifting
      }elseif(strlen($num_ar[1])==$shift){
      $num_ar[0].=$num_ar[1];
      $num_ar[1]="";
 			//if not enough chars on right of radix for shifting
      }else{
      $num_ar[0].=$num_ar[1];
      for($i=strlen($num_ar[1]);$i<$shift;$i++){
        $num_ar[0].="0";
        }
      $num_ar[1]="";
      }
     
    //return positive shifted
    if(strlen($num_ar[1])>0)$num_ar[0].=".".$num_ar[1];
    return $num_ar[0];
    

    }else{
    $shift=abs($shift);

    //if more than enough chars on left of radix for shifting
    if(strlen($num_ar[0])>$shift){
      $num_ar[1]=substr($num_ar[0],strlen($num_ar[0])-$shift).$num_ar[1];
      $num_ar[0]=substr($num_ar[0],0,strlen($num_ar[0])-$shift);

			//if just enough chars on left of radix for shifting
      }elseif(strlen($num_ar[0])==$shift){

      $num_ar[1]=$num_ar[0].$num_ar[1];
      $num_ar[0]="0";

 			//if not enough chars on right of radix for shifting
      }else{
      $num_ar[1]=$num_ar[0].$num_ar[1];
      for($i=strlen($num_ar[0]);$i<$shift;$i++){
        $num_ar[1]="0".$num_ar[1];
        }
      $num_ar[0]="0";
      }

    //return negative shifted
    return $num_ar[0].".".$num_ar[1];
    }

  return NULL;
  }

function scinote_remove_radix($snar){
  if(count($snar)!=3)return NULL;
  if(!@$snar['val'])return NULL;
  if(!@$snar['base'])return NULL;
  if(!@$snar['pow'])$snar['pow']=0;

  $nar=explode(".",$snar['val']);
  if(count($nar)>2)return NULL;
  if(count($nar)<2)return $snar;

  $snar['val']=radix_shifter($snar['val'],strlen($nar[1]));

  $snar['pow']-=strlen($nar[1]);
  return $snar;
  }


function trim_zeros($num){
  $num=str_replace(" ","0",trim(str_replace("0"," ",$num)));
  if(substr($num,0,1)==".")$num="0".$num;
  if(substr($num,-1)==".")$num=substr($num,0,strlen($num)-1);
  return $num;
  }
?>
	