<?php

require_once("config.php");

if(@$_POST['new_num']){
  $num_str=$_POST['txt_num'];
  $xtra=$_POST['xtra'];
  if(!is_numeric($xtra))$xtra=0;
  $num=binnum_draw_prep($num_str,$snar,$duar,$xtra);
  $hpos=2;
  $hspace=2;

  $x=10;
  $y=0;
  $fvsize=20;
  $fstroke=2;

  $rstr=draw_unum($duar['exp'],$hpos,$y,$fvsize,$fstroke,$hpos);
  }

	?>
	<form action=<?php echo $_SERVER['PHP_SELF'];?> method=post>
<input type=hidden size=100 name=new_num value=1>
<input type=text size=100 name=txt_num value="<?php echo @$num_str;?>">Number<br>
<input type=text size=4 name=xtra value="<?php echo @$xtra;?>"> Extra Precision<br>
<input type=submit>
</form>
<?php
    $hpos=2;
    $hspace=2;

    $x=10;
    $y=0;
    $fvsize=20;
    $fstroke=2;
    

    $rstr=NULL;
    if($duar){
      $rstr=draw_unum($duar['co'],$hpos,$y,$fvsize,$fstroke,$hpos);
      $hpos+=5;
      $rstr.=draw_unum($duar['exp'],$hpos,$y,$fvsize,$fstroke,$hpos);
      }
?>
<svg height="200" width="1000">
    <line x1="0" y1="0" x2="200" y2="0" style="stroke:rgb(255,0,0);stroke-width:1" />
    <line x1="0" y1="20" x2="200" y2="20" style="stroke:rgb(255,0,0);stroke-width:1" />
    <?php 
    //$val="1100";
    if($rstr)echo $rstr;
    $hpos+=$hspace;
    ?>

  </svg>
<?php
if($duar){
  ar_dump($snar,"sci note vals");
  ar_dump($duar,"uscript drawing vals");
  }
?>