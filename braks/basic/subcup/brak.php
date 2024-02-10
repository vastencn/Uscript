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
  
  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  $chunk_x_offset=$branch_length+($stroke_width/2)+$hpad;  
  $inner_space_size=@$chunk['width']+$hpad*2;
  $inner_space_end=$chunk_x_offset+@$inner_space_size;
  $svg_str="";

  //draw open
  $svg_str.=svg_hline(0,0,$branch_length,$stroke_width);
  $svg_str.=svg_hcup($branch_length,0,$height,0-$inner_space_size,$stroke_width);

  //now embed the original chunk svg
  $svg_str.=draw_svg_symbol(@$chunk['svg'],0,$chunk_x_offset);


  $nchunk['svg']=$svg_str;
  $nchunk['drawn']=TRUE;
  $nchunk['height']=$height+$stroke_width;
  $nchunk['width']=$inner_space_end;

  return $nchunk;
  }
?>