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
$brak_funk_name="eval_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="eval";

//a defintion of the brakets menaing
$brak_text_def =  "the evaluation braket\n".
          "it looks like a normal braket with an embeded dot\n".
          "the dot radical encompansses many meanings, including particle,integer,radix,true.. \n".
          "here it represents \"true\"\n".
          "the eval braket reduces its contents to a true or false value";



function eval_brak($chunk,$dot_radius=5,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  if(!chunk_is_drawable($chunk))return NULL;

  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  $svg_str="";

  //draw open
  $svg_str.=eval_brak_draw_open($dot_radius,$height,$cup_depth);

  //n0w embed the original chunk svg
  $chunk_x_offset=$dot_radius*2+($stroke_width/2)+$hpad;
  $svg_str.=draw_svg_symbol(@$chunk['svg'],0,$chunk_x_offset);

  //draw close
  $inner_space_end=$chunk_x_offset+@$chunk['width']+$hpad;
  $svg_str.=svg_hcup($inner_space_end+($stroke_width/2),0,$height,$cup_depth,$stroke_width);

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



function eval_brak_draw_open($dot_radius=6,$cup_width=10,$cup_depth=5,$stroke_width=2){
  $nsvg="";

  $nsvg.=svg_dot($dot_radius,0,$dot_radius);
  $nsvg.=svg_hcup($dot_radius,0,$cup_width,0-$cup_depth,$stroke_width);
  
  return $nsvg;
  }
?>