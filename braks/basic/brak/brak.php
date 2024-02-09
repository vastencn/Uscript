<?php
//-------------------------------------------------------------------------------------------------------
//brak brak
//
//this is a braket php file
//it contains at least one main function fo rthe braket
//there are some variables that can be set as gobal variables and will be imported into the braket definiton
//
//-------------------------------------------------------------------------------------------------------




//REQUIRED
//name of the main braket function to be called
//some code may enact a default to be the same as the spelling name, but it should be considered required
$brak_funk_name="brak_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="brak";

//a defintion of the brakets menaing
$brak_text_def = 	"the most fundamental brak\n".
				  	"The simple bracket we all know, it determines operational order in math, code, markup, etc..";



function brak_brak($chunk,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  if(!chunk_is_drawable($chunk))return NULL;
  
  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  $svg_str="";

  //draw open
  $svg_str.=svg_hcup(0,0,$height,0-$cup_depth,$stroke_width);

  //now embed the original chunk svg
  $chunk_x_offset=($stroke_width/2)+$hpad;
  $svg_str.=draw_svg_symbol(@$chunk['svg'],0,$chunk_x_offset);

  //draw close
  $inner_space_end=$chunk_x_offset+@$chunk['width']+$hpad;
  $svg_str.=svg_hcup($inner_space_end+($stroke_width/2),0,$height,$cup_depth,$stroke_width);

  $nchunk['svg']=$svg_str;
  $nchunk['drawn']=TRUE;
  $nchunk['height']=$height+$stroke_width;
  $nchunk['width']=$inner_space_end+$stroke_width;

  return $nchunk;
  }


?>