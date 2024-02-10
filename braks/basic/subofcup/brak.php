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
$brak_funk_name="subofcup_brak";

//argument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//argument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="subofcup";

//if FALSE or unset the name lbael is on the left side of the brack, if TRUE the name label is on the right side
$brak_default_label_right=TRUE;

//a definition of the brakets menaing
$brak_text_def =  "the most fundamental named braket, in a 'cuping form'\n".
          "draws \"(x)subof y\" as x]-y .. except \"]-\" is combined without a gap\n".
          "unline the default form of this brak, this is an alternate drawing\n".
          "the cup is one sided, and the cup extends the full length of the braketed object\n".
          "defined by example usages\n".
          "there are several different usages, all somewhat simmilar in nature\n".
          "the overall symbol inherits a somewhat abstract general meaning from these various usages";



function subofcup_brak($chunk,$branch_length=8,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  if(!chunk_is_drawable($chunk))return NULL;
  
  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  $svg_str="";

  

  //now embed the original chunk svg
  $chunk_x_offset=$hpad;
  $svg_str.=draw_svg_symbol(@$chunk['svg'],0,$chunk_x_offset);

  //draw close
  $inner_space_size=@$chunk['width']+$hpad*2;
  $inner_space_end=$chunk_x_offset+$inner_space_size-$hpad;
  $svg_str.=subofcup_brak_draw_close($inner_space_end,$branch_length,$height,$inner_space_size);

  //svg_hcup($inner_space_end+($stroke_width/2),0,$height,$cup_depth,$stroke_width);

  $nchunk['svg']=$svg_str;
  $nchunk['drawn']=TRUE;
  $nchunk['height']=$height+$stroke_width;
  $nchunk['width']=$inner_space_end+$stroke_width+$branch_length;

  return $nchunk;
  }



function subofcup_brak_draw_close($xoffset,$branch_length=6,$cup_width=10,$cup_depth=5,$stroke_width=2){
  $nsvg="";

  $nsvg.=svg_hcup($xoffset,0,$cup_width,$cup_depth,$stroke_width);
  $nsvg.=svg_hline($xoffset,0,$branch_length,$stroke_width);
  
  return $nsvg;
  }
?>