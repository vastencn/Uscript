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

  $segs=explode("into",$chunk['content']);
  $chaunks=array();
  foreach($segs as $seg){
    
    $retained=NULL;
    $consumed=NULL;
    $expelled=NULL;

    $sar=explode(" retained ",$seg);
    if(count($sar)>1){      
      $retained=render_uscript_text($sar[0]);
      $seg=$sar[1];
      }

    $sar=explode(" consumed ",$seg);
    if(count($sar)>1){      
      $consumed=render_uscript_text($sar[0]);
      $seg=$sar[1];
      }

    $sar=explode(" expelled ",$seg);
    if(count($sar)>1){      
      $expelled=render_uscript_text($sar[0]);
      $seg=$sar[1];
      }


    
    

    $new_chunk=render_uscript_text($seg);
    $new_chunk['retained']=$retained;
    $new_chunk['consumed']=$consumed;
    $new_chunk['expelled']=$expelled;
    $chunks[]=$new_chunk;
    //$chunks[]=pre_render_into_chunk($seg);
    //$chunks[]=$new_chunk;

    }

//ar_dump($chunks);


  $btype="convert";
  $argc=count($chunks);
  $last_arg=$argc-1;

  $stroke=2;

  $chunk_max_height=20;
  $zigzag_height=5;
  $zigzag_overlap=4;
  $brak_vspace=3;
  $arc_size=5;

  $seg_min_width=20;
  $subseg_min_width=20;

  $brak_vsize=($brak_vspace+$zigzag_height);
  


  foreach($chunks as $tchunk){
    if($tchnunk['height']>$chunk_max_height)$chunk_max_height=$tchnunk['height'];
    }

  $brak_height=$chunk_max_height+$brak_vsize*2;

  $bot_y=($brak_height/2)-$stroke/2;
  $top_y=0-$bot_y;
  $bot_y_arc=$bot_y-$arc_size;
  $top_y_arc=0-$bot_y_arc;
  $bot_zigzag_top=$bot_y-$zigzag_height;
  $top_zigzag_bot=0-$bot_zigzag_top;

  $bot_arc_top=$bot_zigzag_top-$arc_size;
  $top_arc_bot=0-$bot_arc_top;
  $bot_y_arc=$bot_y-$arc_size;
  $vline_len_half=$bot_arc_top;
  $vline_full_half=$bot_y-$arc_size;

  $xpos=0;

  $first_width=$chunks[0]['width'];
  if($first_width<$seg_min_width)$first_width=$seg_min_width;
  
  $svg_str.=$chunks[0]['svg'];

  $svg_str.=svg_hzigzag($xpos,$top_y,$first_width,$zigzag_height,$zigzag_overlap);
  $xpos+=$first_width;

  $now_tb="bot";

  for($i=1;$i<$argc;$i++){
    $this_chunk=$chunks[$i];
    $zigzag_width=$this_chunk['width'];  

    if($this_chunk['retained'])$zigzag_width+=$this_chunk['retained']['width']+$arc_size*2;
    
    if($this_chunk['consumed']){
      $zigzag_width+=$this_chunk['consumed']['width']+$arc_size*2;
      }

    if($this_chunk['expelled']){
      $zigzag_width+=$this_chunk['expelled']['width']+$arc_size*2;
      }

    if($now_tb=="top")$zigzag_width-=$arc_size*2;
    $this_width=$zigzag_width+$arc_size*2;
    if($this_width<$seg_min_width)$this_width=$seg_min_width;
    $this_zig_drop=(($this_width/2)+$xpos+$arc_size*2)-$zigzag_overlap;
    $this_xstart=$xpos;


    // retained, expeled, consumed 

 
    switch($now_tb){
      case "bot":
                 $svg_str.=svg_arc($xpos,$top_zigzag_bot,1,1,$arc_size,1);
                 $xpos+=$arc_size;  
                 $svg_str.=svg_vexline($xpos,0,$vline_len_half);  
                 $xpos+=$arc_size;
                 $svg_str.=svg_arc($xpos,$bot_zigzag_top,-1,-1,$arc_size,1);
                 $svg_str.=svg_hzigzag($xpos,$bot_zigzag_top,$zigzag_width,$zigzag_height,$zigzag_overlap);
                 

                 if($this_chunk['retained']){
                   $tmp_width=$this_chunk['retained']['width'];

                   $xpos2=$xpos;
                   $svg_str.=svg_hline($xpos2,$top_zigzag_bot,$tmp_width);
                   $xpos2+=$tmp_width;
                   $svg_str.=svg_arc($xpos2,$top_zigzag_bot,1,1,$arc_size,1);
                   $xpos2+=$arc_size;  
                   $vline_len= abs($top_arc_bot)+abs($bot_zigzag_top);
                   if($xpos2>=$this_zig_drop)$vline_len+=$zigzag_height;
                   $svg_str.=svg_vline($xpos2,$top_arc_bot, $vline_len );

                   $svg_str.=draw_svg_symbol($this_chunk['retained']['svg'],0,$xpos);
                   $xpos+=$tmp_width;
                   $xpos+=$arc_size*2;
                   }


                 if($this_chunk['consumed']){
                   $tmp_width=$this_chunk['consumed']['width'];

                   $xpos2=$xpos;
                   $svg_str.=svg_hzigzag($xpos,$top_y,$tmp_width,$zigzag_height,$zigzag_overlap);
                   $xpos2+=$tmp_width;
                   $svg_str.=svg_arc($xpos2,$top_zigzag_bot,1,1,$arc_size,1);
                   $xpos2+=$arc_size;  
                   $vline_len= abs($top_arc_bot)+abs($bot_zigzag_top);
                   if($xpos2>=$this_zig_drop)$vline_len+=$zigzag_height;
                   $svg_str.=svg_vline($xpos2,$top_arc_bot, $vline_len );

                   $svg_str.=draw_svg_symbol($this_chunk['consumed']['svg'],0,$xpos);
                   $xpos+=$tmp_width;
                   $xpos+=$arc_size*2;
                   }


                 if($this_chunk['expelled']){
                   $tmp_width=$this_chunk['expelled']['width']+$arc_size;

                   $xpos2=$xpos;


                   $vline_len= abs($top_y_arc)+abs($bot_zigzag_top);
                   if($xpos2>=$this_zig_drop)$vline_len+=$zigzag_height;
                   $svg_str.=svg_vline($xpos2,$top_y_arc, $vline_len );
                   $xpos2+=$arc_size;  
                   $svg_str.=svg_arc($xpos2,$top_y,-1,1,$arc_size,0);

                   $svg_str.=svg_hzigzag($xpos2,$top_y,$tmp_width,$zigzag_height,$zigzag_overlap);
                   $xpos2+=$tmp_width; 

                   $svg_str.=draw_svg_symbol($this_chunk['expelled']['svg'],0,$xpos+$arc_size);
                   $xpos+=$tmp_width;
                   $xpos+=$arc_size;
                   }

                 $svg_str.=draw_svg_symbol($this_chunk['svg'],0,$xpos);
                 $xpos=$this_xstart+$this_width;
                 $now_tb="top";
                 break;
      case "top":
                 $svg_str.=svg_arc($xpos,$bot_y,1,-1,$arc_size,0);
                 $xpos+=$arc_size;  
                 $svg_str.=svg_vexline($xpos,0,$vline_full_half);  
                 $xpos+=$arc_size;
                 $svg_str.=svg_arc($xpos,$top_y,-1,1,$arc_size,0);
                 $svg_str.=svg_hzigzag($xpos,$top_y,$this_width,$zigzag_height,4);



                 if($this_chunk['retained']){
                   $tmp_width=$this_chunk['retained']['width'];

                   $xpos2=$xpos;
                   $svg_str.=svg_hline($xpos2,$bot_zigzag_top,$tmp_width);
                   $xpos2+=$tmp_width;
                   $svg_str.=svg_arc($xpos2,$bot_zigzag_top,1,-1,$arc_size,0);
                   $xpos2+=$arc_size;  
                   $vline_len= abs($bot_arc_top)+abs($bot_zigzag_top);
                   if($xpos2<=$this_zig_drop)$vline_len+=$zigzag_height;
                   $svg_str.=svg_vline($xpos2,$bot_arc_top, 0-$vline_len );

                   $svg_str.=draw_svg_symbol($this_chunk['retained']['svg'],0,$xpos);
                   $xpos+=$tmp_width;
                   $xpos+=$arc_size*2;
                   }


                 if($this_chunk['consumed']){
                   $tmp_width=$this_chunk['consumed']['width'];

                   $xpos2=$xpos;
                   $svg_str.=svg_hzigzag($xpos,$bot_zigzag_top,$tmp_width,$zigzag_height,$zigzag_overlap);
                   $xpos2+=$tmp_width;
                   $svg_str.=svg_arc($xpos2,$bot_y,1,-1,$arc_size,0);
                   $xpos2+=$arc_size;  
                   $vline_len=abs($bot_y_arc)+abs($bot_y_arc);
                   if($xpos2<=$this_zig_drop)$vline_len+=$bot_zigzag_top;
                   $svg_str.=svg_vline($xpos2,$bot_y_arc, 0-$vline_len );

                   $svg_str.=draw_svg_symbol($this_chunk['consumed']['svg'],0,$xpos);
                   $xpos+=$tmp_width;
                   $xpos+=$arc_size*2;
                   }


                 if($this_chunk['expelled']){
                   $tmp_width=$this_chunk['expelled']['width']+$arc_size;

                   $xpos2=$xpos;


                   $vline_len=abs($bot_y_arc)+abs($bot_zigzag_top)-$arc_size;
                   if($xpos2<=$this_zig_drop)$vline_len+=$bot_zigzag_top;
                   $svg_str.=svg_vline($xpos2,$top_y_arc, $vline_len );
                   $xpos2+=$arc_size;  

                   $svg_str.=svg_arc($xpos2,$bot_zigzag_top,-1,-1,$arc_size,1);
                   $svg_str.=svg_hzigzag($xpos2,$bot_zigzag_top,$tmp_width,$zigzag_height,$zigzag_overlap);
                   $xpos2+=$tmp_width; 

                   $svg_str.=draw_svg_symbol($this_chunk['expelled']['svg'],0,$xpos+$arc_size);
                   $xpos+=$tmp_width;
                   $xpos+=$arc_size;
                   }

                 $svg_str.=draw_svg_symbol($this_chunk['svg'],0,$xpos);
                 $xpos=$this_xstart+$this_width+$arc_size*2;
                 $now_tb="bot";
                 break;

      }

    }

  $full_height=($bot_y+1)*2;
  $full_width=$xpos;


  //if($argc<2)return NULL;

  //ar_dump($chunk['words_x'],"brak");

  
  //$chain_height=20;
  //$chain_half_height=$chain_height/2;
  //$nowx=0;
  //$nowy

  //$svg_str.=svg_hline($x=0,$y=0,$l=5,$s=2)
  //$svg_str.=svg_hzigzag(0,0,20,5,0.5);


  //$svgstr


  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $height=@$chunk['height']+($vpad*2);
  if($minh&&$height<$minh)$height=$minh;

  //draw open
  //$svg_str.=svg_hcup($xstart,0,$height,0-$cup_depth,$stroke_width);
  if($dot){
    //$svg_str.=svg_dot($xstart,0,4);
    }
  if($circ){
    //$svg_str.=svg_circle($xstart,0,4,2,"white");
    }

  //now embed the original chunk svg
  $chunk_x_offset=$xstart+$cstart+$hpad;
  //$svg_str.=draw_svg_symbol(@$chunk['svg'],0,$chunk_x_offset);

  //draw close
  $inner_space_end=$chunk_x_offset+@$chunk['width']+$hpad;
  //$svg_str.=svg_hcup($inner_space_end+($stroke_width/2),0,$height,$cup_depth,$stroke_width);

  $nchunk['svg']=$svg_str;
  $nchunk['brak']['spelling']=$chunk['brak']['spelling'];
  $nchunk['drawn']=TRUE;
  $nchunk['height']=$full_height;
  $nchunk['width']=$full_width;
  $nchunk['brako_xstart']=0;
  $nchunk['brako_xend']=$chunk_x_offset;
  $nchunk['brakc_xstart']=$chunk_x_offset+$chunk['width'];
  $nchunk['brakc_xend']=$nchunk['width'];

  return $nchunk;
  }



?>