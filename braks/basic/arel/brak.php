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
$brak_funk_name="arel_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="arel";

//a defintion of the brakets menaing
$brak_text_def = 	"array element brak\n".
				  	"";



function arel_brak($chunk,$cup_depth=6,$hpad=3,$vpad=2,$stroke_width=2){
  if(!chunk_is_drawable($chunk))return NULL;

  //ar_dump($chunk);

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

  $subtype=NULL;
  //ar_dump($chunk);
      
      if($chunk['words'][0]==","){
        $subtype="pre";
        }else if($chunk['words'][count($chunk['words'])-1]==","){
        $subtype="post";
        } elseif(count($chunk['words'])>2) {
        for($i=1;$i<count($chunk['words'])-1;$i++){
          $wordar1=array($chunk['words'][0]);
          if($chunk['words'][$i] == ","){
            $wordar2=array("_4");
            for($j=$i+1;$j<count($chunk['words']);$j++){
              $wordar2[]=$chunk['words'][$j];
              $wstr1=implode(" ",$wordar1);
              $wstr2=implode(" ",$wordar2);
              $line1=render_uscript_text($wstr1);
              $line2=render_uscript_text($wstr2);
              $l1end=$line1['width'];
              append_elsa($line1,$line2);
              $chunk=$line1;
              $subtype="iline";
              $iline=$l1end+5;
              }
            break;
            }  
          $wordar1[]=$chunk['words'][$i];

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
  if($closed)$cup_depth=$height;



  $svg_str.=svg_vcup($xstart,$height/2,$inner_space_width,$cup_depth,$stroke_width);
  $svg_str.=svg_vcup($xstart,0-$height/2,$inner_space_width,0-$cup_depth,$stroke_width);



  $nchunk['brak']['spelling']=$chunk['brak']['spelling'];
  $nchunk['drawn']=TRUE;
  $nchunk['height']=$height+$stroke_width;

  if($subtype=="pre"){
    $svg_str.=svg_vline($xstart,($height/2)+8,$l=0-($height+16),$s=2);
    $nchunk['height']+=16;
    }
  if($subtype=="post"){
    $svg_str.=svg_vline($xstart+$inner_space_width,($height/2)+8,$l=0-($height+16),$s=2);
    $nchunk['height']+=16;
    }
  if($subtype=="iline"){
    $svg_str.=svg_vline($chunk_x_offset+$iline,($height/2)+8,$l=0-($height+16),$s=2);
    $nchunk['height']+=16;
    }


  $nchunk['svg']=$svg_str;
  $nchunk['width']=$inner_space_end+$stroke_width+1;
  $nchunk['brako_xstart']=0;
  $nchunk['brako_xend']=$chunk_x_offset;
  $nchunk['brakc_xstart']=$chunk_x_offset+$chunk['width'];
  $nchunk['brakc_xend']=$nchunk['width'];

  return $nchunk;
  }


?>