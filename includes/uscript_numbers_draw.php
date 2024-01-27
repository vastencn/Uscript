<?php

//this code was cobbled together earlier in hatchety cobbled sporadic dev period of the bin number svg drawing function
//the actual drawing function and the data struct need an upgrade, but they work for now and are an isloated subsystem
//so they have just been slated for upgrade later when the project has been fully fleshed out

function draw_circ($x,$y,$r,$stroke,&$hpos){
	$cx=$x+$r;
	$cy=$y+$r;
	$cr=$r-$stroke/2;
	$hpos+=$r*2;
	return "<circle cx=$cx cy=$cy r=$cr stroke=black; stroke-width=$stroke fill=none />";
}

function draw_dot($x,$y,$r,&$hpos){
	$cx=$x+$r;
	$cy=$y+$r;
	$cr=$r;
	$hpos+=$r*2;
	return "<circle cx=$cx cy=$cy r=$cr stroke=none fill=black />";
}

function draw_circ_cen($x,$y,$r,$stroke){
	$cx=$x;
	$cy=$y;
	$cr=$r-$stroke/2;
	return "<circle cx=$cx cy=$cy r=$cr stroke=black; stroke-width=$stroke fill=none />";
}

function draw_dot_cen($x,$y,$r){
	$cx=$x;
	$cy=$y;
	$cr=$r;
	return "<circle cx=$cx cy=$cy r=$cr stroke=none fill=black />";
}

function draw_semi_circle($x,$y,$r){
	return "<path
     style=\"fill:none;stroke:#000000;stroke-width:2\"
     d=\"m 2.1643134,4.1801885 c -2.3129689,0 -4.1880019,-1.875033 -4.1880019,-4.18800182 0,-2.31296918 1.875033,-4.18800138 4.1880019,-4.18800138\"
     id=path832 transform=\"translate($x,$y) rotate($r)\" />";
}

function draw_polys($x,$y,$vsize,$stroke,$pts,&$hpos=NULL){
	$rstr="";
	foreach ($pts as $par){
		$rstr.=draw_poly($x,$y,$vsize,$stroke,$par,$hpos);
	}
	return $rstr;
}

function lines_combine($line1,$line2,$space){
	$c=count($line1[0]);
	$l2_start=$line1[0][$c-1][0]+$space;

	foreach ($line2[0] as $pt){
		$line1[0][]=[$pt[0]+$l2_start,$pt[1]];
	}

	$c2=count($line2);
	if($c2>1){
		for($i=1;$i<$c2;$i++){
			for ($j=0;$j<count($line2[$i]);$j++){
				$line2[$i][$j][0]+=$l2_start;
			}	  
			$line1[]=$line2[$i];
		}


	}

	return $line1;
}

function draw_poly($x,$y,$vsize,$stroke,$pts,&$hpos=NULL){
	$pstr="";
	$mx=0;
	$hsize=$vsize;
	$vsize-=$stroke;
	$y+=$stroke/2;
	foreach ($pts as $pt){
		$px=$pt[0]*$hsize+$x;
		$py=$pt[1]*$vsize+$y;
		if($px>$mx)$mx=$px;
		$pstr.="$px,$py ";
	}
	$nhpos=$mx+$stroke/2;
	if($hpos && $nhpos>$hpos)$hpos=$nhpos;
	return "<polyline points=\"$pstr\" style=\"fill:none;stroke:black;stroke-width:$stroke\" />";
}



function f2bin($num,$mxdps=100){
	$neg=0;
	if($num<0){
		$neg=1;
		$num=abs($num);
	}

	$dpts="";
	$ival=floor($num);
	$dval=$num-$ival;
	$ival=decbin($ival);
	if($dval>0){
		$dps=1;
		$ckval=1;
		while($dps<$mxdps&&$dval>0){
			$ckval/=2;
			if($dval>=$ckval){
				$dpts.="1";
				$dval-=$ckval;
			}else{
				$dpts.="0";
			}
			$dps++;
		}
	}
	return array($neg,$ival,$dpts);
}

function bin_streak($str){
	$rar=array();
	$sar=str_split($str);
	$rar[0]=array($sar[0],1);
	$c=count($sar);
	$l=0;
	for($i=1;$i<$c;$i++){
		if($sar[$i]==$rar[$l][0]){
			$rar[$l][1]++;	
		}else{
			$l++;
			$rar[$l]=array($sar[$i],1);
		}
	}
	return $rar;
}

function draw_unum_seg($seg,&$lines,&$segoff,$yval,$vlen,$hlen,$pre,$post,$x,$y,$fv){
	$yval=($yval/2)+0.5;
	$vlen/=2;
	$segoff+=$pre;
	if($seg[1]==1){
		$lines[0][]=[$segoff,$yval];
	}else if($seg[1]==2){
		$lines[0][]=[$segoff,$yval];
		$segoff+=$hlen;
		$lines[0][]=[$segoff,$yval];
	}else if($seg[1]>2){
		$lines[0][]=[$segoff,$yval];
		$segoff+=$hlen;
		for($i=2;$i<$seg[1];$i++){
			$lines[]=	[
				[$segoff,$yval-$vlen],
				[$segoff,$yval+$vlen]
			];
			$segoff+=$hlen;
		}
		$lines[0][]=[$segoff,$yval];
	}

	$segoff+=$post;
	return;
}

function draw_unum($nv,$x,$y,$fv,$fs,&$hpos){
	$nval=$nv;
//echo "!!!";
//print_r($nval);
//echo "!!!";
	//	0  0
	//	1  1
	//	2  .
	//	3  /
	//  4  /. 
	//	5	^*.
	//	9  -
	//	8  ^/
	//	7  ^*

  //some default vsizes
	$vsize=[
					0.6, 	//0 0 bit run over 2
					0.6, 	//1 1 bit run over 2
					0.7, 	//2 radix
					0.6, 	//3 division
					0.6, 	//4	division + radix
					0,		//5 empty 
					0, 		//6 empty
					0.6, 	//7 sci note *
					0.6, 	//8 sci note /
					0 		//9 negative
				];

  //some default hsizes
				$hsize=[
					[0.2, 0,		0], 		//0 0 bit run over 2
					[0.2, 0,		0], 		//1 1 bit run over 2
					[0.5, 0,		0], 		//2 radix
					[0.5, 0,		0], 		//3 division
					[0.6, 0,		0], 		//4	division + radix
					[0, 	0,		0],			//5 empty 
					[0, 	0,		0],			//6 empty
					[0.5, 0,		0], 		//7 sci note *
					[0.5, 0,		0], 		//8 sci note /
					[0, 	0,		0]			//9 negative
				];

				$xtras="";

				//if negative add negative symbol
				if($nval[0]==1){
					$segs=[[9,0]];
				}else{
					$segs=array();
				}

//echo "<hr>Starting segs1<pre>";
//print_r($segs);
//echo "</pre><hr>";
				// if sci note negative then place it at start
				if($nval[5]<0){
					$segs=[[7,0]];
				}


//echo "<hr>Starting segs2<pre>";
//print_r($segs);
//echo "</pre><hr>";
				//if num add num bits
				if(strlen($nval[1])>0){
					$segs=array_merge($segs,bin_streak($nval[1]));
				}

//echo "<hr>Starting segs3<pre>";
//print_r($segs);
//echo "</pre><hr>";
				//if num rad add num rad bits
				if(strlen($nval[2])>0){
					if(strlen($nval[1])<1){
						$segs[0]=[5,0];
					}else{
						$segs[]=[2,0];
					}
					$segs=array_merge($segs,bin_streak($nval[2]));
				}


//echo "<hr>Starting segs4<pre>";
//print_r($segs);
//echo "</pre><hr>";
				//if dnum or drad 
				if(strlen($nval[3])>0||strlen($nval[4])){
					//if no dnum and it's followed by a radix then combine division symbol with radix
					if(strlen($nval[3])<1&&strlen($nval[4])>0){
						$segs[]=[4,0];			
					}else{
						$segs[]=[3,0];			
					}
				}

//echo "<hr>Starting segs5<pre>";
//print_r($segs);
//echo "</pre><hr>";

				//if dnum add dnum bits
				if(strlen($nval[3])>0){
					$segs=array_merge($segs,bin_streak($nval[3]));
				}

//echo "<hr>Starting segs6<pre>";
//print_r($segs);
//echo "</pre><hr>";
				//if drad add drad bits
				if(strlen($nval[4])>0){
					//if the radix comes right after fraction symbol then it is included already with the fractions symbol
					if(strlen($nval[3])>0){
						$segs[]=[2,0];			
					}
					$segs=array_merge($segs,bin_streak($nval[4]));
				}

//echo "<hr>Starting segs7<pre>";
//print_r($segs);
//echo "</pre><hr>";
				//if sci not positive then do sci not symbol at end
				if($nval[5]>0){
					$segs[]=[8,0];
				}

				$c=count($segs);
				$l=$c-1;

//echo "<hr>Starting input end<pre>";
//print_r($nval);
//echo "</pre><hr>";
//echo "<hr>Starting segs<pre>";
//print_r($segs);
//echo "</pre><hr>";

	//find starting v vals
	for($i=0;$i<$c;$i++){
		if($segs[$i][0]<2){
			if($segs[$i][1]<3){
				$segs[$i][2]=1;
			}else{
				$segs[$i][2]=$vsize[$segs[$i][0]];	
			}
		if($segs[$i][0]==0)$segs[$i][2]=0-$segs[$i][2];  //flip sign if it is a 0
		}
	}

	  //  echo "b";print_r($segs);
	//add vpos for no bit segs
	for($i=0;$i<$c;$i++){
		$nxt=$i+1;
		$prev=$i-1;

		$last=($i==$l);
		$first=($i==0);
//echo "<hr>i=$i , nxt=$nxt<br><pre>";
//print_r($segs);
//echo "</pre><hr>";
		if($segs[$i][0]>1){

			if($first){
				$vp=$segs[$nxt][2];
				$segs[$i][2]=[$vp,$vp];
			}else if($last){
				$vp=$segs[$prev][2];
				$segs[$i][2]=[$vp,$vp];
			}else{
				$vps=$segs[$prev][2];
				$vpe=$segs[$nxt][2];
				$segs[$i][2]=[$vps,$vpe];	
			}
			
			if($segs[$i][2][0]==$segs[$i][2][1]){
				$hl=(($segs[$i][2][0]>0)*2)-1;
				$segs[$i][2][0]=$vsize[$segs[$i][0]]*$hl;
				$segs[$i][2][1]=$vsize[$segs[$i][0]]*$hl;
			}
		}
	}

	   // echo "c";print_r($segs);
//print_r($segs);
		//find starting h vals
	for($i=0;$i<$c;$i++){
		$segs[$i][3]=$hsize[$segs[$i][0]];	
	}

//print_r($segs);

		//clean up hlens
	$pprevy=0;
	$prevy=0;
	for($i=0;$i<$c;$i++){

		$val=$segs[$i][0];
		$bits=$segs[$i][1];
		$yval=$segs[$i][2];
		if($val>1)$yval=$yval[1];
		$yval=1-2*($yval>0);

		$nxt=$i+1;
		$prev=$i-1;

		$nnxt=$i+2;
		$pprev=$i-2;

		$last=($i==$l);
		$first=($i==0);

		$llast=($nnxt>=$l);
		$ffirst=($pprev<0);

		//if last bit 
		if($last){
			//there is no next
			$nxty=0;
		}else{
			//if not last then set next vals
			$nxty=$segs[$nxt][2];
			if($segs[$nxt][0]>1)$nxty=$nxty[0];
		}
		$nxty=1-2*($nxty>0);  //nexty is -1 if nxty, nxty is +1 if no nxty 

		//if not second last
		if($llast){
			//there is no next-next
			$nnxty=0;
		}else{
			$nnxty=$segs[$nnxt][2];
			if($segs[$nnxt][0]>1)$nnxty=$nnxty[0];
		}
		$nnxty=1-2*($nnxty>0);//nexty is -1 if nxty, nxty is +1 if no nxty 
		//nxty becomes inverted 1 or 0 of the positivity of the nxty val


		$prev_flat=0;
		$nxt_flat=0;

		//if not last char
		if(!$last){

			//if next char is 1 or 0
			if($segs[$nxt][0]<2){
				
				//if its a streak
				if($segs[$nxt][1]>1){
					$nxt_flat=1;

				//if it not a streak
				}else{
					if(!$llast&& $segs[$nnxt][0]>1){

						if(($segs[$nnxt][2][0]>0) == ($segs[$nnxt][2][1]>0))$nxt_flat=1;
					}
				}

			// if nxt is NOT a number then it has a start and end point so chec if it is fglat or slanted
			}else if($segs[$nxt][0]>1 && ($segs[$nxt][2][0]>0) == ($segs[$nxt][2][1]>0)){
				$nxt_flat=1;
			}
		}



		if(!$first){
			if($segs[$prev][0]<2){

				//if its a streak
				if($segs[$prev][1]>1){
					$prev_flat=1;

				//if it not a streak
				}else{
					if(!$ffirst && $segs[$pprev][0]>1){
						if(($segs[$pprev][2][0]>0) == ($segs[$pprev][2][1]>0))$prev_flat=1;
					}
				}

			// if nxt is NOT a number then it has a start and end point so chec if it is fglat or slanted
			}else if($segs[$prev][0]>1 && ($segs[$prev][2][0]>0) == ($segs[$prev][2][1]>0)){
				$prev_flat=1;
			}
		}

        //Third array element is Hlen, Pre, Post.. Hvals

		//BITS
		if($val<2){
	    	//single bits
		    if($bits==1){


		    //if it is a spike
			if($prevy != $yval || $nxty != $yval){
				//ad pre h buf
				$segs[$i][3][1]=0.4;

				//if next is fland AND next not a signle bit(next not a bit OR next is a streak)
				if($nxt_flat&&($segs[$nxt][0]>1||$segs[$nxt][1]>1)){
					//add post buf
					$segs[$i][3][2]=0.4;
				}
			}

			if($first && $nxt_flat){
				$segs[$i][3][1]=0;
				$segs[$i][3][2]=0;
			}

			if($last && $prev_flat){
				$segs[$i][3][1]=0;
				$segs[$i][3][2]=0;
			}

			//if not first and previous was not a bit
			if(!$first && $segs[$prev][0]>1){
				//remove pre buf
				$segs[$i][3][1]=0;
			}

			//if not last and next is not a bit
			if(!$last && $segs[$nxt][0]>1){
				//remove post buf
				$segs[$i][3][2]=0;
			}

			//if prev and next are flat, make line vertical
			if($prev_flat && $nxt_flat && $prevy != $nxty){
				$segs[$i][3][1]=0;
				$segs[$i][3][2]=0;
			}

			//streaks
			}else{
				//DOUBLE streak
				if($bits==2){
					//just a pre buf
					$segs[$i][3][0]=0.4;
					$segs[$i][3][1]=0;
					$segs[$i][3][2]=0;
				//multi streak
				}else{
					//no buf, no change to len
					$segs[$i][3][1]=0;
					$segs[$i][3][2]=0;
				}

				if(!$last && $segs[$nxt][0]>1 && $nxt_flat){
					$segs[$i][3][0]=0.2;
					$segs[$i][1]++;
				}
				if(!$first && $segs[$prev][0]>1 && $prev_flat){
					$segs[$i][3][0]=0.2;
					$segs[$i][1]++;
				  
				}else{
			//	echo "($i  , $prev_flat)";	
				}

			  }
          
		  }

	$pprevy=$prevy;
	$prevy=$yval;
	}


	  //  echo "e";print_r($segs);

	//build vpos array
	$vpos_ar=array();
	for($i=0;$i<$c;$i++){
		if($segs[$i][0]<2){
			$vpos_ar[]=$segs[$i][2];
		}else{
			$vpos_ar[]=$segs[$i][2][0];
			$vpos_ar[]=$segs[$i][2][1];
		}
	}

	// equalize vpos
	$c2=count($vpos_ar);
	$l2=$c-1;
	$yv=$vpos_ar[0];
	$hl=($yv>0);
	$run_start=0;
	for($i=1;$i<$c2;$i++){
		$this_yv=$vpos_ar[$i];
		$this_hl=($this_yv>0);

		if($this_hl==$hl){

			if(abs($yv)>abs($this_yv)){
				$yv=$this_yv;
			}

		}else{
			for($j=$run_start;$j<$i;$j++){
				$vpos_ar[$j]=$yv;
			}
			$run_start=$i;
			$yv=$this_yv;
			$hl=$this_hl;
		}
	}

	for($j=$run_start;$j<$i;$j++){
		$vpos_ar[$j]=$yv;
	}



	$vi=0;
	for($i=0;$i<$c;$i++){
		if($segs[$i][0]<2){
			$segs[$i][2]=$vpos_ar[$vi++];
		}else{
			$segs[$i][2][0]=$vpos_ar[$vi++];
			$segs[$i][2][1]=$vpos_ar[$vi++];
		}
	}


	
	$lines=array();
	$rstr="";
	$segoff=0;


						//print_r($segs);

	for($i=0;$i<$c;$i++){
		$now=$segs[$i];
		$val=$now[0];
		$bits=$now[1];
		$hvals=$now[3];
		if($val<2){
			$yval=$now[2];
		}else{
			$yval=$now[2][1];
		}




	  //  echo "d";print_r($segs);

		$vlen=0;
		$hlen=$hvals[0];
		$pre=$hvals[1];
		$post=$hvals[2];
		$vlen=0.5;		

		switch($val){
			case 0:
			case 1:
							draw_unum_seg($now,$lines,$segoff,$yval,$vlen,$hlen,$pre,$post,$x,$y,$fv);
							break;
			case 2:
							$y1=($now[2][0]+1)/2;
							$y2=($now[2][1]+1)/2;
							$pxpos=($segoff*$fv)+$x;
							$lines[0][]=[$segoff,$y1];
							$segoff+=$hlen;
							$usable_height=$fv-$fs;
							$xtras.=draw_dot_cen( $pxpos+($hlen*$fv)/2 , ((($y1+$y2)/2)*$usable_height)+$fs/2 , 3 );
							$lines[0][]=[$segoff,$y2];
							break;
			case 3:
							$rot=0;
							$y1=($now[2][0]+1)/2;
							$y2=($now[2][1]+1)/2;
							$pxpos=($segoff*$fv)+$x;
							$lines[0][]=[$segoff,$y1];
							$segoff+=$hlen;
							$usable_height=$fv-$fs;
							if($y1>$y2){
								$rot=-65;
							}else if($y1<$y2){
								$rot=65;
							}
							$xtras.=draw_semi_circle( $pxpos+($hlen*$fv)/2 , ((($y1+$y2)/2)*$usable_height)+$fs/2 , $rot );
							$lines[0][]=[$segoff,$y2];
							break;
			case 4:
							$rot=0;
							$y1=($now[2][0]+1)/2;
							$y2=($now[2][1]+1)/2;
							$pxpos=($segoff*$fv)+$x;
							$lines[0][]=[$segoff,$y1];
							$segoff+=$hlen;
							$usable_height=$fv-$fs;
							if($y1>$y2){
								$rot=-65;
							}else if($y1<$y2){
								$rot=65;
							}
							$xtras.=draw_semi_circle( $pxpos+($hlen*$fv)/3 , ((($y1+$y2)/3)*$usable_height)+$fs/2 , $rot );
							$xtras.=draw_dot_cen( $pxpos+($hlen*$fv)/1.5 , ((($y1+$y2)/1.5)*$usable_height)+$fs/2 , 3 );
							$lines[0][]=[$segoff,$y2];
							break;
			case 7:
							$yn=($now[2][1]+1)/2;
							$pxpos=($segoff*$fv)+$x;
							$lines[0][]=[$segoff,$yn];
							$lines[]=[
												[$segoff+0.25,$yn-0.25],
												[$segoff,$yn],
												[$segoff+0.25,$yn+0.25]
												];
							$segoff+=$hlen;						
							$lines[0][]=[$segoff,$yn];
							break;
			case 8:
							$yn=($now[2][1]+1)/2;
							$pxpos=($segoff*$fv)+$x;
							$lines[0][]=[$segoff,$yn];
							$segoff+=$hlen;						
							$lines[]=[
												[$segoff-0.25,$yn-0.25],
												[$segoff,$yn],
												[$segoff-0.25,$yn+0.25]
												];
							$lines[0][]=[$segoff,$yn];
							break;

		}

	}


	//    echo "<br><br>ec(((<pre>";print_r($segs)."</pre><br><br>";
	//kill redundant points in main line
	//$nline=array($lines[0][0]);
	
	$nline=array();
	$cl=count($lines[0]);
	$ly=$lines[0][0][1];

	for($i=0;$i<$cl;$i++){
		$this_p=$lines[0][$i];
		for($j=$i+1;$j<$cl && ($lines[0][$j][1]==$this_p[1]);$j++){
		}
		$j--;
		$nline[]=$this_p;
		if($j>$i){
			$nline[]=$lines[0][$j];
			$i=$j;
		}
	}

	$lines[0]=$nline;
	return $xtras.draw_polys($x,$y,$fv,$fs,$lines,$hpos);
}

/*
echo bcpow('2', '100', 2);
echo "<br>";
echo bcadd('10000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001.43000000000000000000000000000000000000000000111111111','212',200);
		$intext=@$_GET['textfield'];
	?>
	<form action=test7.php method=GET>
		<input type=text size=200 name=textfield value="<?php echo $intext;?>">
		<input type=text size=20 name=num value="<?php echo @$_GET['num'];?>">.<input type=text size=20 name=rad value="<?php echo @$_GET['rad'];?>"> / 
		<input type=text size=20 name=dnum value="<?php echo @$_GET['dnum'];?>">.<input type=text size=20 name=drad value="<?php echo @$_GET['drad'];?>">e<input type=text size=2 name=exp value="<?php echo @$_GET['exp'];?>">
		<input type=submit>
	</form>
	<pre>
		<?php

		$hpos=2;
		$hspace=2;

		$x=10;
		$y=0;
		$fvsize=20;
		$fstroke=2;

		$ival=array(0,@$_GET['num'],@$_GET['rad'],@$_GET['dnum'],@$_GET['drad'],@$_GET['exp']);
		$rstr=draw_unum($ival,$hpos,$y,$fvsize,$fstroke,$hpos);
		$rstr.=draw_unum($ival,$hpos,$y,$fvsize,$fstroke,$hpos);


		$str = preg_replace( '/[^0-9\.\/]/', '', $intext);
		echo "(($intext)$str)";


		?>
	</pre>

	<svg height="1000" width="1000">
		<line x1="0" y1="0" x2="200" y2="0" style="stroke:rgb(255,0,0);stroke-width:1" />
		<line x1="0" y1="20" x2="200" y2="20" style="stroke:rgb(255,0,0);stroke-width:1" />
		<?php 

		$hpos=2;
		$hspace=2;

		$x=10;
		$y=0;
		$fvsize=20;
		$fstroke=2;
//for($i=0;$i<9;$i++){

	//$ival=array(0,@$_GET['num'],@$_GET['rad'],@$_GET['dnum'],@$_GET['drad'],@$_GET['exp']);

		echo $rstr;
	//echo draw_unum($ival,$hpos,$y,$fvsize,$fstroke,$hpos); 
//echo  draw_semi_circle(10,10,0);
		$hpos+=$hspace;
//}




		?>

	</svg>halo
	*/
	?>
