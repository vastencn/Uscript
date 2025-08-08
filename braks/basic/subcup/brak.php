<?php
//-------------------------------------------------------------------------------------------------------
//sub brak
//
//this is a braket php file
//it contains at least one main function fo rthe braket
//there are some variables that can be set as gobal variables and will be imported into the braket definiton
//
//-------------------------------------------------------------------------------------------------------




//REQUIRED
//name of the main braket function to be called
//some code may enact a default to be the same as the spelling name, but it should be considered required
$brak_funk_name="subcup_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="subcup";

//a defintion of the brakets menaing
$brak_text_def = 	"the most fundamental named braket, in a 'cuping form'\n".
					"draws \"x sub(y)\" as x-[y .. except \"-[\" is combined without a gap\n".
					"unline the default form of this brak, this is an alternate drawing\n".
          "the cup is one sided, and the cup extends the full length of the braketed object\n".
					"defined by example usages\n".
					"there are several different usages, all somewhat simmilar in nature\n".
					"the overall symbol inherits a somewhat abstract general meaning from these various usages";



function subcup_brak($chunk,$branch_length=8,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  if(!chunk_is_drawable($chunk))return NULL;
  

  $yesdot=NULL;
  $nodot=NULL;
  $cstart=0;
  $opts=@$chunk['brak']['opts'];
  if(@count($opts)>0){
    foreach($opts as $opt){
      $vname=$opt[0];
      $vval=$opt[1];
      switch($vname){
        case "yes":
                   $yesdot=1;
                   break;                   
        case "branchlen":
                   $branch_length=$vval;
                   break;                   
        case "tax":
                   $tax=1;
                   break;
        case "part":
                   $part=1;
                   break;
        case "inter":
                   $inter=1;
                   break;
        case "no":
                   $nodot=1;
                   break;
        case "eq":
                   $eqdots=1;
                   break;
        case "neq":
                   $neqdots=1;
                   break;
        case "approx":
                   $adots=1;
                   break;
        case "irshift":
                   $cstart=$vval;
                   break;
        }
      }
    }


  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  $chunk_x_offset=$cstart+$branch_length+($stroke_width/2)+$hpad;  
  $inner_space_size=@$chunk['width']+$hpad*2;
  $inner_space_end=$cstart+$chunk_x_offset+@$inner_space_size;
  $svg_str="";

  //draw open
  if($tax){
    if($height<22)$height=22;
    $top_hlen=8;
    $bot_hlen=8;
    $vert_h=13;
    $indent=$top_hlen+$bot_hlen;
    $chunk_x_offset=$indent+($stroke_width/2)+$hpad;
    $branch_length=$indent;
    $svg_str.=svg_hline(0,0-($top_hlen/2),$top_hlen,$stroke_width);
    $svg_str.=svg_vline($top_hlen,3,0-$vert_h,$stroke_width);
    $svg_str.=svg_hline($top_hlen-($stroke_width/2),3,$bot_hlen,$stroke_width);
    }else if($part){
    $circle_rad=8;
    $branch_length=15;
    $circle_center=$circle_rad+($stroke_width/2);
    $svg_str.=svg_circle($circle_center,0,$circle_rad,2,"white");
    $svg_str.=svg_dot($circle_center,0,3);
    $svg_str.=svg_hline($circle_center,0,$branch_length,$stroke_width);
    $branch_length+=$circle_center;
    $chunk_x_offset=$branch_length+($stroke_width/2)+$hpad;
    }else if($inter){
    $ziglen=8;
    $branch_length=20;
    $img=brak_load_opt_img("inter",$chunk['brak']);
    $svg_str.=$img['svg'];
    $branch_length+=$circle_center;
    $chunk_x_offset=$branch_length+($stroke_width/2)+$hpad;
    }else{
    $svg_str.=svg_hline(0,0,$branch_length,$stroke_width);
    }

  $inner_space_end=$chunk_x_offset+@$inner_space_size;

  $svg_str.=svg_hcup($branch_length,0,$height,0-($inner_space_size+$cstart),$stroke_width);

  //now embed the original chunk svg
  $svg_str.=draw_svg_symbol(@$chunk['svg'],0,$chunk_x_offset);

  if($yesdot){
    $svg_str.=svg_dot($branch_length,0,4);
    }
  if($nodot){
    $svg_str.=svg_circle($branch_length,0,4,2,"white");
    }

  if($eqdots){
    $svg_str.=svg_dot($branch_length-6,6,3);
    $svg_str.=svg_dot($branch_length-6,-6,3);
    }
    
  if($neqdots){
    $svg_str.=svg_dot($branch_length-6.5,-6,3);
    $svg_str.=svg_circle($branch_length-6.5,6,4,2,"white");
    }
  if($adots){
    $svg_str.=svg_circle($branch_length-6.5,-6,4,2,"white");
    $svg_str.=svg_circle($branch_length-6.5,6,4,2,"white");
    }

  $nchunk['svg']=$svg_str;
  $nchunk['drawn']=TRUE;
  $nchunk['height']=$height+$stroke_width;
  $nchunk['width']=$inner_space_end+$stroke_width;
  $nchunk['brako_xstart']=0;
  $nchunk['brako_xend']=$chunk_x_offset;
  $nchunk['brakc_xstart']=$chunk_x_offset+$chunk['width'];
  $nchunk['brakc_xend']=$nchunk['width'];

  return $nchunk;
  }
?>