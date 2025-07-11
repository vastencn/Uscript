<?php
//-------------------------------------------------------------------------------------------------------
//arel brak
//
//this is a braket php file
//it contains at least one main function fo rthe braket
//there are some variables that can be set as gobal variables and will be imported into the braket definiton
//
//-------------------------------------------------------------------------------------------------------




//REQUIRED
//name of the main braket function to be called
//some code may enact a default to be the same as the spelling name, but it should be considered required
$brak_funk_name="exp_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="exp";

//a defintion of the brakets menaing
$brak_text_def = 	"expression brak\n".
				  	"";



function exp_brak($chunk,$cup_depth=13,$hpad=3,$vpad=2,$stroke_width=2){
  if(!chunk_is_drawable($chunk))return NULL;

  $xstart=0;
  $cstart=($stroke_width/2);
  $svg_str="";
  $minh=NULL;
  $closed=NULL;

  $opts=@$chunk['brak']['opts'];
  if(@count($opts)>0){
    foreach($opts as $opt){
      $vname=$opt[0];
      $vval=$opt[1];
      switch($vname){
        case "img":
                   $img=brak_load_opt_img($vval,$chunk['brak']);
                   $svg_str.=$img['svg'];
                   break;
        case "rshift":
                   $xstart=$vval;
                   break;
        case "irshift":
                   $cstart=$vval;
                   break;
        case "sublen":
                   $svg_str.=svg_hline($xstart,0,$vval);
                   $xstart+=$vval;
                   $closed=TRUE;
                   break;
        case "minh":
                   $minh=$vval;
                   break;
        case "def":
                   $chunk['brak']['spelling']=$vval;
                   break;
        }
      }

    }


  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  if($minh&&$height<$minh)$height=$minh;

  //draw open
  //$svg_str.=svg_hcup($xstart,0,$height,0-$cup_depth,$stroke_width);

  //now embed the original chunk svg
  $chunk_x_offset=$xstart+$cstart+$hpad;
  $svg_str.=draw_svg_symbol(@$chunk['svg'],0,$chunk_x_offset);

  //draw close
  $inner_space_end=$chunk_x_offset+@$chunk['width']+$hpad;
  $inner_space_width=$inner_space_end-$xstart;
  $cup_depth=$height-2;
  $svg_str.=svg_vcup($xstart,$height/2,$inner_space_width,$cup_depth,$stroke_width);
  //$svg_str.=svg_vcup($xstart,0-$height/2,$inner_space_width,0-$cup_depth,$stroke_width);

  $nchunk['svg']=$svg_str;
  $nchunk['brak']['spelling']=$chunk['brak']['spelling'];
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