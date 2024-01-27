<?php

// dependancies math.php and strings.php


function gen_uscript_number($num_str_in){

  //to lower and strip spaces
  $num_str=strtolower($num_str_in);
  //$num_str=str_replace(" ","",$num_str);
  $num_str=str_replace("0x","h",$num_str);
  $num_str=str_replace("hex","h",$num_str);
  
  
  //cant do this, only for first chars
  //$num_str=str_replace("0b","b",$num_str);
  
  $num_str=str_replace("bin","b",$num_str);
  
  //cant do this, only for first chars
  //$num_str=str_replace("dec","",$num_str);
  //$num_str=str_replace("d","",$num_str);

  //we accept input of 'e' exponential notation, 
  //but we convert to using 's because 'e' conflicts with a flexbile hex format
  $num_str=str_replace("*10^","s",$num_str);
  $num_str=str_replace("<<","s+",$num_str);
  $num_str=str_replace(">>","s-",$num_str);

  $nar=space_split($num_str);
  $exp_el=1;

  //if the base prefix was separated by space
  if($nar[0]=="h"||$nar[0]=="b"){
    $exp_el=2;
    }

  if(count($nar)>$exp_el){
    $fc=substr($nar[$exp_el],0,1);
    if($fc=="e"){
      $nar[$exp_el]="s".substr($nar[$exp_el],1);
      }
    }

  $num_str=implode($nar);
  //done dealing with the problem of 'e' notation conflcting ith hex
  //our rule is "if you instston using e wit hex, the there must be a space before the e.. otherwise please use 's', '*10^', or '<<'/'>>'."
  
  if(strlen($num_str)<1)return NULL;

  //check for base
  switch(substr($num_str,0,1)){
    case "h":$base=16;
             $num_str=substr($num_str,1);
             $reg_cmd='/[^s+\-.0-9a-f]/';
             $mx_ch='f';
             break;
    case "b":$base=2;
             $num_str=substr($num_str,1);
             $num_str=str_replace("e","s",$num_str);
             $reg_cmd='/[^s+\-.0-1]/';
             $mx_ch='1';
             break;
    default :$base=10;
             $num_str=str_replace("e","s",$num_str);
             $reg_cmd='/[^s+\-.0-9]/';
             $mx_ch='9';
             break;
    }
  
  
  $str = preg_replace( $reg_cmd, '', $num_str);
  $str_ar=str_split($str);
  $arc=count($str_ar);

  $new_str="";

  if($str_ar[0]=='-'){
    $new_str="-";
    }else if(($str_ar[0]>='0'&&$str_ar[0]<='9')||($str_ar[0]>='a'&&$str_ar[0]<='f')){
    $new_str="".$str_ar[0];
    }

  //first chunck of digits

  for(
        $i=1            ;  
            ($i<$arc) 
            &&
            ($str_ar[$i]>='0')
            &&
            ($str_ar[$i]<=$mx_ch)
                        ;
        $i++
      ){
    $new_str.=$str_ar[$i];
    }

  //decimal and second chunck of digits
  if($i<$arc&&$str_ar[$i]=='.'){
    $new_str.='.';
    $i++;

    for(
                          ;  
              ($i<$arc) 
              &&
              ($str_ar[$i]>='0')
              &&
              ($str_ar[$i]<=$mx_ch)
                          ;
          $i++
        ){
      $new_str.=$str_ar[$i];
      }

    }

  $neg=0;
  $exp="";
  //sci note
  if($i<$arc&&$str_ar[$i]=='s'){
    $i++;
    if($str_ar[$i]=='+'){
      $i++;
      }elseif($str_ar[$i]=='-'){
      $neg=1;
      $i++;
      }

    for(
                          ;  
              ($i<$arc) 
              &&
              ($str_ar[$i]>='0')
              &&
              ($str_ar[$i]<=$mx_ch)
                          ;
          $i++
        ){
      $exp.=$str_ar[$i];
      }

    }

  if($neg){
    $pow=0-$exp;
    }else{
    $pow=$exp;
    }
  $num=array();
  $num['val']=$new_str;
  $num['base']=$base;
  $num['pow']=$pow;
  //echo "{{ $new_str exp [neg= $neg] $exp }}";

  return $num;
  }

/*

if(@$_POST['new_num']){
  
  $num_str=$_POST['txt_num'];

  //echo "[".preg_replace ("/ a-zA-Z0-9]/", "", $num_str)."]";
  
  
  //echo "$num_str [\"$num_str\"] (".gen_uscript_number($num_str).")";
  echo "<pre>{";
  print_r(gen_uscript_number($num_str));

  $bstr="1111222233334";
  echo "


  ";

  print_r(chunk_4bits_left($bstr));
  echo "


  ";

  print_r(chunk_4bits_right($bstr));

  echo "}</pre>";

echo "((".chunk2hex("1010")."))[".dechexc(15)."]";
  }


//echo "###".space_contract("  a  b c    d  e   f  g  i    ")."###";

?>

<form action=uscript_numbers.php method=post>
<input type=hidden size=100 name=new_num value=1>
<input type=text size=100 name=txt_num value="<?php echo @$num_str;?>">
<input type=submit>
</form>
*/
?>