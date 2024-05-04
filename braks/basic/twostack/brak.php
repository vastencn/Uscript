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
  $iff=FALSE;
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
        case "interact":
                   $btype="interact";
                   break;
        default:
                break;    
        }
      }

    }

  if(!$btype)return NULL;

  $top=$chunks[0];
  $bot=$chunks[1];

  $new_svg="";


  //horizontal specs
  $hstart=4;
  $maxwidth=$top['width'];

  //vertical spaces
  $vspace=4;
  $vcenter=$bot['height']+($vspace/2);
  $total_height=$bot['height']+$top['height']+$vspace;
  

  if($bot['width']>$maxwidth)$mnaxwidth=$bot['width'];
  
  $new_svg=svg_vline(($total_height/2),1,$total_height,2);

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