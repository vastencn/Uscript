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
$brak_funk_name="chain_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="chain";

//a defintion of the brakets menaing
$brak_text_def = 	"chain brak for lengthy chains of args\n";



function chain_brak($chunk,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  $xstart=0;
  $cstart=($stroke_width/2);
  $svg_str="";
  $minh=NULL;
  $opts=@$chunk['brak']['opts'];
  $dot=$circ=FALSE;
  if(@count($opts)>0){
    foreach($opts as $opt){
      $vname=$opt[0];
      $vval=$opt[1];
      switch($vname){
        case "img":
                   $img=brak_load_opt_img($vval,$chunk['brak']);
                   $svg_str.=$img['svg'];
                   break;

        case "def":
                   $chunk['brak']['spelling']=$vval;
                   break;
        }
      }

    }

  $btype="convert";
  $argc=count($chunk);
  //if($argc<2)return NULL;

  //ar_dump($chunk['words_x'],"brak");

  
  //$chain_height=20;
  //$chain_half_height=$chain_height/2;
  //$nowx=0;
  //$nowy

  //$svg_str.=svg_hline($x=0,$y=0,$l=5,$s=2)
  $svg_str.=svg_hzigzag(0,0,20,5,0.5);


  //$svgstr


  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  if($minh&&$height<$minh)$height=$minh;

  //draw open
  $svg_str.=svg_hcup($xstart,0,$height,0-$cup_depth,$stroke_width);
  if($dot){
    $svg_str.=svg_dot($xstart,0,4);
    }
  if($circ){
    $svg_str.=svg_circle($xstart,0,4,2,"white");
    }

  //now embed the original chunk svg
  $chunk_x_offset=$xstart+$cstart+$hpad;
  $svg_str.=draw_svg_symbol(@$chunk['svg'],0,$chunk_x_offset);

  //draw close
  $inner_space_end=$chunk_x_offset+@$chunk['width']+$hpad;
  $svg_str.=svg_hcup($inner_space_end+($stroke_width/2),0,$height,$cup_depth,$stroke_width);

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