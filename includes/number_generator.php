<?php
// dependancies : uscript_numbers.php and uscript_numbers_draw.php


function make_base($snar, $base){
  global $radix_shifted;
  if(count($snar)!=3)return NULL;
  if(!@$snar['val'])return NULL;
  if(!@$snar['pow'])$snar['pow']=0;
  if(!@$snar['base'])return NULL;
  
  if($snar['base']==$base)return $snar;
  $snar=scinote_remove_radix($snar);

  $nsnar=array();

  switch($base){
    case 10:
           switch($snar['base']){
              case 16:$snar['val']=arb_hexbin($snar['val']);
                      $snar['pow']*=4;
                      echo "<hr>";
                      print_r($snar);
              case 2: $newv=arb_bindec($snar['val']);
                      $mul=bcpow("2",abs($snar['pow']),200);
                      if($snar['pow']<0)$mul=bcdiv("1",$mul,200);
                      $newv=trim_zeros(bcmul($newv,$mul,200));
                      $nsnar['val']=$newv;
                      $nsnar['base']=10;
                      $nsnar['pow']=0;
                      return $nsnar;
                      break;
              }

           break;
    case 2:
           switch($snar['base']){
              case 16:$nsnar['val']=arb_hexbin($snar['val']);
                      $nsnar['pow']=0;
                      if($snar['pow'])$nsnar['pow']=$snar['pow']*4;
                      $nsnar['base']=2;
                      return $nsnar;
              case 10://fist find what the number is multiplied by in decimal
                      $pow_mul=bcpow("10",abs($snar['pow']),200);

                      //next find the closest binary power equivalent 
                      $bin_pow=find_closest_bin_power($pow_mul);

                      //ratio of powers(because we only have decimanl arbitrary div and mult funcs)
                      $bin_pow_val=bcpow("2","0".$bin_pow);
                      $pow_ratio=bcdiv("0".$pow_mul,$bin_pow_val,200);
                      
                      //we are going to change the power multiplier
                      //so we ned to value by the corrent ratio
                      if($snar['pow']>0){
                        $nval=bcmul($snar['val'],$pow_ratio);
                        $npow=$bin_pow;
                        }else{
                        $nval=bcdiv($snar['val'],$pow_ratio);
                        $npow=0-$bin_pow;
                        }

                      //$nsnar['val']=arb_decbin($nval);

                      //make new bin num
                      $nsnar['val']=arb_decbin($nval);
                      $nsnar['pow']=$npow;
                      $nsnar['base']=2;
                      return $nsnar;
              }

           break;

    
    }
  return NULL;
  }


function make_bin_number($num_str,$extra_precision=0){
  $num=gen_uscript_number($num_str);
  if($num['base']==2)return $num;

  //ar_dump($num,"make bin gen_uscript_num out");

  $pow1=$num['pow'];
  if(!$pow1)$pow1=0;
  $num=scinote_remove_radix($num);
  //ar_dump($num,"make bin scinote_remove_radix out");

  $pow2=$num['pow'];
  if(!$pow2)$pow2=0;
  $base=$num['base'];

  $pow_dif=$pow1-$pow2;
  $pow_val=bcpow("0".$base,"0".$pow_dif);

  echo "[pow val=$pow_val, pow1 $pow1, pow2 $pow2]";

  //decide how many bits to shift so that the radix it is in the sme magnitude range
  $bin_pow_shift=find_closest_bin_power($pow_val);

  echo "(bin pow shift $bin_pow_shift)";

  if($pow_dif>0){
    if($base==10){
      $pow_val=pow(10,$pow_dif);
      $pow_shift=1;
      }else if($base==16){
      $pow_shift=$pow_dif*4;  
      }else{
      return NULL;  
      }
    }

  if($extra_precision>0){
    $extra_mult=bcpow("2","0".$extra_precision);
    $num['val']=bcmul("0".$num['val'],$extra_mult);
    $num=make_base($num,2);
    $num=scinote_radix_shift($num,0-$extra_precision);
    $num['pow']-=$extra_precision;
    }else{
    $num=make_base($num,2);
    }
  //ar_dump($num,"make bin make_base out");
  
  $num=scinote_radix_shift($num,0-$bin_pow_shift);
  return $num;
  }

$ival="000";


$duar=NULL;
function binnum_draw_prep($num_str,&$snar,&$duar,$extrap=0){
  //we have to create a snar and a number draw array
  $snar=make_bin_number($num_str,$extrap);
  ar_dump($snar,"snar");
  $duar=scinote_2_unum($snar);
 
  return;
  }

?>