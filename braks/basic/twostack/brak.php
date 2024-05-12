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
$brak_funk_name="twostack_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=2;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="twostack";

//a defintion of the brakets menaing
$brak_text_def =  "the twostack braket\n";



function twostack_brak($chunks,$dot_radius=5,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  $yesno=1;
  $rel=FALSE;
  $hastype=NULL;
  $opts=@$chunks[0]['opts'];
  $btype="if";
  $def="unnamed_brak";
  $cond_opts=array();
  $sub_opts=array();
  if(@count($opts)>0){
    foreach($opts as $opt){
      $vname=$opt[0];
      $vval=$opt[1];
      switch($vname){
        case "def":
                   $def=$vval;
                   break;
        case "interact":
                   $btype="interact";
                   break;
        case "rinteract":
                   $rel=TRUE;
                   break;
        case "give":
                   $btype="give";
                   break;
        case "get":
                   $btype="get";
                   break;
        case "rel":
                   $rel=1;
                   break;
        default:
                break;    
        }
      }

    }

  if(!$btype)return NULL;


  $new_svg="";

  if($btype=="give"||$btype=="get"){

    $arrow_len=20;
    $arrow_head=8;
    $arrow_gap=2;
    $sidegap=4;
    $left=$chunks[0];
    $right=$chunks[1];

    $maxheight=18;
    if($left['height']>$maxheight)$maxheight=$left['height'];
    if($right['height']>$maxheight)$maxheight=$right['height'];

    $total_height=$maxheight+2;

    $leftx=$sidegap;
    $arrowx=$leftx+$left['width']+$arrow_gap;
    $arrowxe=$arrowx+$arrow_len;
    $rightx=$arrowxe+$arrow_gap;
    $total_width=$rightx+$right['width']+$sidegap;


    $new_svg.=svg_vline(0,(0-$total_height/2),$total_height,2);
    $new_svg.=svg_vline($total_width,(0-$total_height/2),$total_height,2);

    $new_svg.=svg_hline($arrowx,0,$arrow_len,2);
    switch($btype){
      case "give":
                  $par=array( 
                    array(($arrowxe-$arrow_head),(0-$arrow_head)), 
                    array($arrowxe,0),
                    array(($arrowxe-$arrow_head),($arrow_head))
                    );
                  break;
      case "get":
                  $par=array( 
                    array(($arrowx+$arrow_head),(0-$arrow_head)), 
                    array($arrowx,0),
                    array(($arrowx+$arrow_head),($arrow_head))
                    );
                  break;

      }
    $new_svg.=svg_polyline($par,2);
    $new_svg.=draw_svg_symbol($left['svg'],0,$leftx);
    $new_svg.=draw_svg_symbol($left['svg'],0,$rightx);




    }else{
    $top=$chunks[0];
    $bot=$chunks[1];

    if($rel){
      $twidth=$chunks[0]['width']+$chunks[1]['width'];
      $bwidth=$chunks[2]['width']+$chunks[3]['width'];

      $txtraw=$bxtraw=0;
      if($twidth>$bwidth){
        $bxtraw=$twidth-$bwidth;
        }else{
        $txtraw=$bwidth-$twidth;
        }

      append_elsa($chunks[0],$chunks[1],25+$txtraw);
      append_elsa($chunks[2],$chunks[3],25+$bxtraw);
      $top=$chunks[0];
      $bot=$chunks[2];
      }


    //horizontal specs
    $diag_len=10;
    $xtra_width=8;
    $hgap=3;



    $hstart=$hgap;
    $maxwidth=$top['width'];
    if($bot['width']>$maxwidth)$maxwidth=$bot['width'];
    $total_width=$maxwidth+$hgap*2+$diag_len+$xtra_width;

    //vertical spaces

    if($bot['height']<7)$bot['height']=7;
    if($top['height']<7)$top['height']=7;
    $vspace=4;
    $vcenter=$bot['height']+($vspace/2);
    $total_height=$bot['height']+$top['height']+$vspace;
    $vcenteroff=($top['height']-$bot['height'])/2;
    $top_y=($vcenteroff-$vspace/2)-$top['height']/2;
    $top_x=$hgap+$diag_len;
    $bot_y=($vcenteroff+$vspace/2)+$bot['height']/2;
    $bot_x=$total_width-($hgap+$diag_len+$bot['width']);
    

    
    $new_svg.=svg_vline(0,(0-$total_height/2),$total_height,2);
    $new_svg.=svg_vline($total_width,(0-$total_height/2),$total_height,2);

    if($top['width']>3)$new_svg.=svg_polyline(array( array(0,$vcenteroff), array($diag_len,$vcenteroff-$diag_len) ),2);
    if($bot['width']>3)$new_svg.=svg_polyline(array( array($total_width,$vcenteroff), array($total_width-$diag_len,$vcenteroff+$diag_len) ),2);
    
    $new_svg.=svg_hline(0,$vcenteroff,$total_width,2);

    $new_svg.=draw_svg_symbol($top['svg'],$top_y,$top_x);
    $new_svg.=draw_svg_symbol($bot['svg'],$bot_y,$bot_x);
    }

  $nchunk['svg']=$new_svg;
  $nchunk['drawn']=TRUE;
  $nchunk['height']=$total_height;
  $nchunk['width']=$total_width;
  $nchunk['brako_xstart']=0;
  $nchunk['brako_xend']=4;
  $nchunk['brakc_xstart']=$total_width-4;
  $nchunk['brakc_xend']=$total_width;
  $nchunk['brak']['spelling']=$def;
  return $nchunk;

  //$topsvg=draw_svg_symbol(@$chunks[0]['svg'],0,0);
  //$botsvg=draw_svg_symbol(@$chunks[1]['svg'],0,0);

  ar_dump($chunks[0],"top");
  ar_dump($topsvg,"top");


  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  
  $subopts=array();
  $brakopts=array();
  switch($btype){
    case "while":
            $brakopts[]=array("img","loop");
            $chunks[0]['brak']['opts']=$brakopts;
            break;
    case "foreach":
            $brakopts[]=array("img","foreach");
            $chunks[0]['brak']['opts']=$brakopts;
            break;
    }
  if($yesno){
    $subopts[]=array("yes","1");
    }else{
    $subopts[]=array("no","1");
    }
  $chunks[1]['brak']['opts']=$subopts;

  if($btype=="if"){
    if($iff){
      $brakopts[]=array("circ","1");
      }else{
      $brakopts[]=array("dot","1");
      }
    $chunks[0]['brak']['opts']=$brakopts;
    }


  $bstruct=search_brak("brak");
  $chunks[0]['brak']['folder']=$bstruct['folder'];

  $bstruct=search_brak("subcup");
  $chunks[1]['brak']['folder']=$bstruct['folder'];

  $chunks[0]['brak']["opts"]=array_merge($cond_opts,$chunks[0]['brak']["opts"]);


  
  switch($btype){
    case "has":
            $cfunc="subofcup_brak";
            $chunks[1]['brak']['opts']=array(array("yes","1"));
            $chunks[0]['brak']['opts']=NULL;
            break;
    case "isin":
            $cfunc="subofcup_brak";
            $chunks[1]['brak']['opts']=NULL;
            $chunks[0]['brak']['opts']=array(array("yes","1"));
           break;
    case "nhas":
            $cfunc="subofcup_brak";
            $chunks[1]['brak']['opts']=array(array("no","1"));
            $chunks[0]['brak']['opts']=NULL;
            break;
    case "nisin":
            $cfunc="subofcup_brak";
            $chunks[1]['brak']['opts']=NULL;
            $chunks[0]['brak']['opts']=array(array("no","1"));
           break;
    default:$cfunc="brak_brak"; 
            break;
    }


//ar_dump($condition,"cond1");
//ar_dump($chunks[1],"chunk1");

  $condition=$cfunc($chunks[0]); 
  $action=subcup_brak($chunks[1]);

  append_elsa($condition,$action,-0);
  


  $nmap=$chunks[0]['defmap'];
  $action_entry=$chunks[1]['defmap'][0];
  $action_entry['xoff']=($condition['brakc_xend']-$condition['brako_xend'])+$action['brako_xend'];
  $nmap[]=$action_entry;
  $condition['defmap_set']=$nmap;
  $condition['brak']['spelling']=$def;


  return $condition;
  }

?>