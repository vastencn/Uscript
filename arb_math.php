<?php
require_once("config.php");

$min_prec=NULL;

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
    case 4:return $fptr(func_get_arg(1),func_get_arg(2),func_get_arg(3));
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
    case "p":return "0.000000000001";
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
	global $min_prec;
	$rzero=0;
	if($min_prec)$rzero=$min_prec;
	$nar=explode(".",$nstr);
	if(count($nar)<2)return $rzero;
	$nar[0]=trim(trim($nar[0]),"0");
	//echo "(".$nar[0].")";
	if(strlen($nar[0])>0){
    return $rzero;
	  }
  $lendif=strlen($nar[1])-strlen(ltrim($nar[1],"0"));
  if($lendif<$min_prec)return $min_prec;
  return 1+$lendif;
  }

function unit_convert($val,$prefix,$units,$preccap=NULL){
  $prenum=pre2num($prefix);
  $preq=precision_required($prenum)+precision_required($val)+precision_required($units);
  $fullval=bcmul("".$val, $prenum,$preq+5);
  if($preccap){
    $preq=$preccap;
    }else{
    $preq+=11;
    }
  $new_units=bcdiv($fullval,"".$units,$preq);
  return $new_units;
  }

function dec_mult($v1,$v2,$extra_prec=10){
  $preq=precision_required($v1)+precision_required($v2);
  return bcmul($v1,$v2,10+3+$extra_prec);
  }

function dec_div($v1,$v2,$extra_prec=10){
  $preq=precision_required($v1)+precision_required($v2);
  return @bcdiv($v1,$v2,10+3+$extra_prec);
  }
$num="0.00000111111";
$nar=create_num($num);
$nar=gen_scinote($nar);
//echo "$num ( ".$nar['num']." * 10^".$nar['pow'] .")";

function str_rad_shift($nstr,$shift){
  $num=create_num($nstr,16);
  $num['pow']=$shift;
  $num=rm_pow($num);
  return $num['num'];
  }
//dec_to_hex($nar);
?>
*This whole page is based on 2018 CODATA electron mass of 9.10938356×10−28 g<br>
Should be updated
<form action=arb_math.php method=post>
<table border=1>
	<tr>
		<td>
				Dec Mult <input type=text name=decmult1 value="<?php echo fetch_var('decmult1');?>">*<input type=text name=decmult2 value="<?php echo fetch_var('decmult2');?>"><?php 
       
        
				echo do_func("dec_mult",fetch_var('decmult1'),fetch_var('decmult2'),10);

				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Dec Div <input type=text name=decdiv1 value="<?php echo fetch_var('decdiv1');?>">/<input type=text name=decdiv2 value="<?php echo fetch_var('decdiv2');?>">prec <input type=text name=decdivp value="<?php echo fetch_var('decdivp');?>"><?php 

        
				echo do_func("dec_div",fetch_var('decdiv1'),fetch_var('decdiv2'),@(0+@fetch_var('decdivp')));

				?>
				<input type=submit>
		</td>
	</tr>
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
				EV to Electrons<input type=text name=ev2e value="<?php echo fetch_var('ev2e');?>"> <input type=text name=evpre value="<?php echo fetch_var('evpre');?>" size=2>eV<?php 

function uunits($uval,$upre,$urat,$pre=NULL){
	$ucount=unit_convert($uval,$upre,$urat,$pre);
  return $ucount;
  }			

function uu_display($uuv,$uname="units"){
	$duuvar=$helects=dec_to_hex(create_num($uuv));
	$duuv=$duuvar['num'];
  $rv="$uname DEC : $uuv<br>";
  $rv.="$uname HEX : $duuv<br>";
  $rv.="Mega $uname : ".str_rad_shift($duuv,-16)."<br>";
  $rv.="Giga $uname : ".str_rad_shift($duuv,-32)."<br>";
  $rv.="Tera $uname : ".str_rad_shift($duuv,-48)."<br>";
  $rv.="Micro $uname : ".str_rad_shift($duuv,16)."<br>";
  $rv.="Nano $uname : ".str_rad_shift($duuv,32)."<br>";
  $rv.="Pico $uname : ".str_rad_shift($duuv,48)."<br>";
  return $rv;
  }
  
  $uu=uunits(fetch_var('ev2e'),fetch_var('evpre'),"510998");
  $utxt=uu_display($uu,"Elects");
  echo "<hr>".$utxt;
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				grams to Electrons<input type=text name=g2e value="<?php echo fetch_var('g2e');?>"> <input type=text name=gpre value="<?php echo fetch_var('gpre');?>" size=2>g<?php 

  $uu=uunits(fetch_var('g2e'),fetch_var('gpre'),"0.000000000000000000000000000910938356",3);
  $utxt=uu_display($uu,"Elects");
  echo "<hr>".$utxt;

				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Daltons to Electrons<input type=text name=da2e value="<?php echo fetch_var('da2e');?>"> <input type=text name=dapre value="<?php echo fetch_var('dapre');?>" size=2>Da<?php 

  $uu=uunits(fetch_var('da2e'),fetch_var('dapre'),"0.00054857990906761589923",3);
  $utxt=uu_display($uu,"Elects");
  echo "<hr>".$utxt;

				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				meters to Hlength<input type=text name=m2h value="<?php echo fetch_var('m2h');?>"> <input type=text name=mpre value="<?php echo fetch_var('mpre');?>" size=2>m<?php 
  $uu=uunits(fetch_var('m2h'),fetch_var('mpre'),"0.21106114");

  $utxt=uu_display($uu,"Hlengths");
  echo "<hr>".$utxt;
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				AU to Hlength<input type=text name=au2h value="<?php echo fetch_var('au2h');?>"> <input type=text name=aupre value="<?php echo fetch_var('aupre');?>" size=2>AU<?php 
  $uu=uunits(fetch_var('au2h'),fetch_var('aupre'),"0.000000000001410856578455");

  $utxt=uu_display($uu,"Hlengths");
  echo "<hr>".$utxt;
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				LY to Hlength<input type=text name=ly2h value="<?php echo fetch_var('ly2h');?>"> <input type=text name=lypre value="<?php echo fetch_var('lypre');?>" size=2>LY<?php 
  $uu=uunits(fetch_var('ly2h'),fetch_var('lypre'),"0.000000000000000022309180101");

  $utxt=uu_display($uu,"Hlengths");
  echo "<hr>".$utxt;
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Seconds to Hticks<input type=text name=s2h value="<?php echo fetch_var('s2h');?>"> <input type=text name=spre value="<?php echo fetch_var('spre');?>" size=2>s<?php 
				if(fetch_var('s2h')){
  				$uu=uunits(fetch_var('s2h'),fetch_var('spre'),"0.00000000070402418376");

				  $utxt=uu_display($uu,"Hticks");
  				echo "<hr>".$utxt;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Hours to Hticks<input type=text name=h2h value="<?php echo fetch_var('h2h');?>"> <input type=text name=hpre value="<?php echo fetch_var('hpre');?>" size=2>h<?php 
				if(fetch_var('h2h')){
  				$uu=uunits(fetch_var('h2h'),fetch_var('hpre'),"0.00000000000019556227");

				  $utxt=uu_display($uu,"Hticks");
  				echo "<hr>".$utxt;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Days to Hticks<input type=text name=d2h value="<?php echo fetch_var('d2h');?>"> <input type=text name=dpre value="<?php echo fetch_var('dpre');?>" size=2>d<?php 
				if(fetch_var('d2h')){
  				$uu=uunits(fetch_var('d2h'),fetch_var('dpre'),"0.00000000000000814842805");

				  $utxt=uu_display($uu,"Hticks");
  				echo "<hr>".$utxt;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Years to Hticks<input type=text name=y2h value="<?php echo fetch_var('y2h');?>"> <input type=text name=ypre value="<?php echo fetch_var('ypre');?>" size=2>Y<?php 
				if(fetch_var('y2h')){
  				$uu=uunits(fetch_var('y2h'),fetch_var('ypre'),"0.000000000000000022309638261");

				  $utxt=uu_display($uu,"Hticks");
  				echo "<hr>".$utxt;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				m/s to LightSpeed<input type=text name=ms2c value="<?php echo fetch_var('ms2c');?>"> <input type=text name=ms2cpre value="<?php echo fetch_var('ms2cpre');?>" size=2>m/s<?php 
				if(fetch_var('ms2c')){
					$min_prec=5;
  				$uu=uunits(fetch_var('ms2c'),fetch_var('ms2cpre'),"299792458.00000000000000");

				  $utxt=uu_display($uu,"c speed");
  				echo "<hr>".$utxt;

					$min_prec=NULL;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				"1m/s per s" to "LightSpeed per Htick"<input type=text name=ms2cc value="<?php echo fetch_var('ms2cc');?>"> <input type=text name=ms2ccpre value="<?php echo fetch_var('ms2ccpre');?>" size=2>m/s2<?php 
				if(fetch_var('ms2cc')){
					$min_prec=10;
  				$uu=uunits(fetch_var('ms2cc'),fetch_var('ms2ccpre'),"425826931449625958.00000000000000");

				  $utxt=uu_display($uu,"LightSpeed per Htick");
  				echo "<hr>".$utxt;

					$min_prec=NULL;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				"g m/s2" to "Electron LightSpeed per Htick"<input type=text name=ms2gcc value="<?php echo fetch_var('ms2gcc');?>"> <input type=text name=ms2gccpre value="<?php echo fetch_var('ms2gccpre');?>" size=2>g m/s2<?php 
				if(fetch_var('ms2gcc')){
					$min_prec=20;
  				$uu=uunits(fetch_var('ms2gcc'),fetch_var('ms2gccpre'),"0.00000000038790208487524");

				  $utxt=uu_display($uu,"Electron LightSpeed per Htick");
  				echo "<hr>".$utxt;

					$min_prec=NULL;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Newtons to "Electron LightSpeed per Htick"<input type=text name=n2gcc value="<?php echo fetch_var('n2gcc');?>"> <input type=text name=n2gccpre value="<?php echo fetch_var('n2gccpre');?>" size=2>N<?php 
				if(fetch_var('n2gcc')){
					$min_prec=20;
  				$uu=uunits(fetch_var('n2gcc'),fetch_var('n2gccpre'),"0.00000000000038790208487524");

				  $utxt=uu_display($uu,"Electron LightSpeed per Htick");
  				echo "<hr>".$utxt;

					$min_prec=NULL;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Newtons Meters/Joules to "Electron LightSpeed per Htick over 1 Hlength"<input type=text name=j2gcc value="<?php echo fetch_var('j2gcc');?>"> <input type=text name=j2gccpre value="<?php echo fetch_var('j2gccpre');?>" size=2>J<?php 
				if(fetch_var('j2gcc')){
					$min_prec=10;
  				$uu=uunits(fetch_var('j2gcc'),fetch_var('j2gccpre'),"0.00000000000183786596090");

				  $utxt=uu_display($uu,"Electron LightSpeed per Htick over 1 Hlength");
  				echo "<hr>".$utxt;

					$min_prec=NULL;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
	<tr>
		<td>
				Electron Volts to "Electron LightSpeed per Htick over 1 Hlength"<input type=text name=ev2gcc value="<?php echo fetch_var('ev2gcc');?>"> <input type=text name=ev2gccpre value="<?php echo fetch_var('ev2gccpre');?>" size=2>eV<?php 
				if(fetch_var('ev2gcc')){
					$min_prec=10;
  				$uu=uunits(fetch_var('ev2gcc'),fetch_var('ev2gccpre'),"11471057.0725");

				  $utxt=uu_display($uu,"Electron LightSpeed per Htick over 1 Hlength");
  				echo "<hr>".$utxt;

					$min_prec=NULL;
  			}
				?>
				<input type=submit>
		</td>
	</tr>
</table>
</form>
<br>
Should make a page that bases all units on the preceeding units<br>
right now eevery unit conversion is its own fixed ratio

