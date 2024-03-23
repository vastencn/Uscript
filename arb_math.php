<?php
require_once("config.php");


function fetch_var($vname){
  return @$_POST[$vname];
  }

function arb_dechex($inv){
  $rv=arb_decbin($inv);
  $rv=float_binhex($rv);
  return strrev($rv);
  }
function arb_hexdec($inv){
  $rv=arb_hexbin($inv);
  $rv=arb_bindec($rv);
  return $rv;
  }

function arb_dec_hex_shift($dv,$sv){
  $mv=bcpow("16",abs($sv));
  $rv=bcmul("$mv","$dv");
  return $rv;
  }

function create_num($nstr,$base=10,$pow=0){
  $rar=array();
  $rar['num']=$nstr;
  $rar['base']=$base;
  $rar['pow']=$pow;
  return $rar;
  }

function clean_num($num){
  $nar=explode(".",$num['num']);
  $nnum=ltrim($nar[0],"0");
  if(count($nar)>1){
    $nar[1]=rtrim($nar[1],"0");
    if(strlen($nar[1])>0)$nnum.=".".$nar[1];
    }
  $num['num']=$nnum;
  return $num;
  }

function make_base_10($nar){
  if($nar['pow']==10)return $nar;
  if($nar['pow']==16){
    //$nar=rm_rad();
    }
  }

function split_num($num){
  $rar=explode(".",$num['num']);
  if(count($rar)<1)$rar[1]="";
  return $rar;
  }


function dec_to_hex($num){
  $num=rm_pow($num);
  $prec=check_precision($num);
  $ori_num=$num['num'];
  $scaler=1;
  if($prec>0){
    $scaler=($prec+2);
    }
  $mult_val=bcpow("16","$scaler");

  $scaled_num=bcmul($ori_num,$mult_val,$scaler);

  $rar2=explode(".",$scaled_num);
  $scaled_int=$rar2[0];
  $scaled_hex=arb_dechex($scaled_int);
  $hex_num=create_num($scaled_hex,16,0-$scaler);
  $rad_hex=rm_pow($hex_num);

  return $rad_hex;
  }


function gen_scinote($num,$precision=3){
  $num=clean_num(rm_rad($num));
  $npow=$num['pow'];
  $nstr=$num['num'];
  $nlen=$precision+1;
  $ldif=strlen($nstr)-$nlen;
  while($ldif<0){
  	$nstr.="0";
  	$npow--;
    $ldif++;
    }
  if($ldif>0){
    $nstr=substr($nstr,0,$nlen);
    $npow+=$ldif;
    }
  
  $npow+=3;
  $nstr=substr($nstr,0,1).".".substr($nstr,1);
  $num['num']=$nstr;
  $num['pow']=$npow;
  return $num;
  };

function check_precision($num){
	$num=clean_num($num);
  $rar=explode(".",$num['num']);
  if(count($rar)<2)return 0;
  return strlen($rar[1]); 
  }

function rm_pow($num){
	$pows=$num['pow'];
	$nar=explode(".",$num['num']);
	if(count($nar)<2)$nar[1]="";
	while($pows>0){
    char_l_shift($nar[0],$nar[1],"0");
    $pows--;
	  }
	while($pows<0){
    char_r_shift($nar[0],$nar[1],"0");
    $pows++;
	  }
	$num['num']=$nar[0].".".$nar[1];
	$num['pow']=0;
	return clean_num($num);
  }

function rm_rad($num){
	$nar=explode(".",$num['num']);
	if(count($nar)<2||strlen($nar[1])<1)return $num;
	$num['num']=$nar[0].$nar[1];
	$num['pow']-=strlen($nar[1]);
  return $num;
  }

function do_func(){
  $fptr=func_get_arg(0);
  switch(func_num_args()){
    case 1:return $fptr();
    case 2:return $fptr(func_get_arg(1));
    case 3:return $fptr(func_get_arg(1),func_get_arg(2));
    }
}

function to_scinote($nstr,$base=10){
  $nar=create_num($nstr,$base);
  $nar=gen_scinote($nar);
  return $nar;
  }

function print_scinote($num){
  return $num['num']." * ".$num['base']."^".$num['pow'];;
  }

function print_num($num){
  $num=rm_pow($num);
  return $num['num'];
  }

function pre2num($pre){
	switch($pre){
    case "n":return "0.000000001";
    case "u":return "0.000001";
    case "m":return "0.001";
    case "K":
    case "k":return "1000";
    case "M":return "1000000";
    case "G":return "1000000000";
    case "T":return "1000000000000";
    case "P":return "1000000000000000";
	  }
  return "1";
  }

function precision_required($nstr){
	$nar=explode(".",$nstr);
	if(count($nar)<2)return 0;
	$nar[0]=trim(trim($nar[0]),"0");
	echo "(".$nar[0].")";
	if(strlen($nar[0])>0){
    return 0;
	  }
  $lendif=strlen($nar[1])-strlen(ltrim($nar[1],"0"));
  return 1+$lendif;
  }

$num="0.00000111111";
$nar=create_num($num);
$nar=gen_scinote($nar);
echo "$num ( ".$nar['num']." * 10^".$nar['pow'] .")";
//dec_to_hex($nar);
?>
<form action=arb_math.php method=post>
<table border=1>
	<tr>
		<td>
				Dec 2 Hex <input type=text name=dechex value="<?php echo fetch_var('dechex');?>"><?php echo do_func("arb_dechex",fetch_var('dechex'));?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Hex 2 Dec<input type=text name=hexdec value="<?php echo fetch_var('hexdec');?>"><?php echo do_func("arb_hexdec",fetch_var('hexdec'));?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Dec Hex Shift<input type=text name=dechexshiftv value="<?php echo fetch_var('dechexshiftv');?>">radix points<input size=4 type=text name=dechexshiftr value="<?php echo fetch_var('dechexshiftr');?>"><?php echo do_func("arb_dec_hex_shift",fetch_var('dechexshiftv'),fetch_var('dechexshiftr'));?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Dec rad to scinote<input type=text name=dec2scinote value="<?php echo fetch_var('dec2scinote');?>"><?php echo print_scinote( do_func("to_scinote",fetch_var('dec2scinote')) );?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				scinote to string<input type=text name=scitodecn value="<?php echo fetch_var('scitodecn');?>">* <input type=text name=scitodecb value="<?php echo fetch_var('scitodecb');?>" size=2>^<input type=text name=scitodece value="<?php echo fetch_var('scitodece');?>" size=3><?php echo print_num( create_num( fetch_var('scitodecn'), fetch_var('scitodecb'), fetch_var('scitodece') ) );?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				EV to Electrons<input type=text name=ev2e value="<?php echo fetch_var('ev2e');?>">* <input type=text name=evpre value="<?php echo fetch_var('evpre');?>" size=2>eV<?php 
				$prenum=pre2num(fetch_var('evpre'));
				$evnum=fetch_var('ev2e');
				$preq=precision_required($prenum)+precision_required($evnum);
				$evs=bcmul("".$evnum, $prenum,$preq+5);
				//$elecs=bcdiv(left_operand, right_operand);
				$elecs=bcdiv($evs,"510998",$preq+11);
				echo "[$elecs , ".print_num(dec_to_hex(create_num($elecs)))."]";
				?>
				<input type=submit>
		</td>
	</tr>
</table>
</form>

