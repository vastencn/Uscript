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
$brak_funk_name="sub_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="sub";

//a defintion of the brakets menaing
$brak_text_def = 	"the most fundamental named braket\n".
				  	"draws \"x sub(y)\" as x-[y] .. except \"-[\" is combined without a gap\n".
					"it is used to describe a general kind of object/property, parent/child, or group/element relationship\n".
					"defined by example usages\n".
					"there are several different usages, all somewhat simmilar in nature\n".
					"the overall symbol inherits a somewhat abstract general meaning from these various usages";



function sub_brak($chunk,$branch_length=8,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  if(!chunk_is_drawable($chunk))return NULL;
  
  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  $svg_str="";

  //draw open
  $svg_str.=sub_brak_draw_open($branch_length,$height,$cup_depth);

  //n0w embed the original chunk svg
  $chunk_x_offset=$branch_length+($stroke_width/2)+$hpad;
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



function sub_brak_draw_open($branch_length=6,$cup_width=10,$cup_depth=5,$stroke_width=2){
  $nsvg="";

  $nsvg.=svg_hline(0,0,$branch_length,$stroke_width);
  $nsvg.=svg_hcup($branch_length,0,$cup_width,0-$cup_depth,$stroke_width);
  
  return $nsvg;
  }
?>