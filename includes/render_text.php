<?php
//here we process brakets
//we rely on array order and array pop in various places here
//so if extended functionality is added later, be careful with array order
//we rely on the top level 'integer index' as our oder, but that is not always in order in PHP
//and I sometimes use old schoold for(i=0;i<x;i++) and sometimes foreach(x as y)
//so if you mess around with array order be aware of the potential consequences if you dont end up with the array properly ordered

//if you want to have access to the parsed text used for the rendering, then just supply the second argument, it will be a pointer to the parsed text

$render_linei=1;

//default gap between rendered words
if(!isset($default_word_spacing))$default_word_spacing=8;

function render_line_with_defmap($istr,$mname=NULL,$href="#"){
  global $render_linei;
  $car=array();

  if(!$mname)$mname="rline$render_linei";

  $imap=create_defmap_imap($mname,$href);
  $elsa=render_uscript_text($istr,$car,$imap);
  $render_linei++;

  $html_str=draw_svg_imgline($elsa,$mname);
  $html_str.=$imap['html'];
  return $html_str;
  }

function render_lines_with_defmap($lines){
  global $render_linei;
  $html="";
  foreach($lines as $line){
    //$html.=render_line_with_defmap($istr,$mname=NULL,$href="#");
    }
  }

function render_line($istr){
  $car=array();
  $elsa=render_uscript_text($istr,$car);
  $html_str=draw_svg_imgline($elsa);
  return $html_str;
  }

function multi_line_render($str){

  $str_lines=explode("\n",$str);
  if(count($str_lines)<1)return NULL;
  
  $render_lines=array();
  foreach($str_lines as $line){
    $elsa=render_uscript_text($line);
    $render_lines[]=$elsa;
    }

  $svg_page=draw_svg_page($render_lines,$y=0,$x=0);

  return $svg_page;
  }

function render_uscript_text($in_str,&$car=NULL,&$defmap=NULL){
  //parse the text
  $car=parse_brackets($in_str);

  parse_prep_words($car);
  parse_prep_brak_funks($car);


  //sort or render sequence by sorting by brak depth
  //we will render from deepest upwards
  $ds=depth_sort($car);
  $fds=flatten_depth_sort($ds);
  $fds_copy=$fds;

  //brak_words_preparse($car);


  //YES, I did just start adding "sa" to "el"(element) because I love the Frozen franchise :D
  while(($elsa=fetch_next_elsa($fds))>=0){
    /*
    if(@$car[$elsa]['brak']['opts'][0][0]=="convert"){
       // decided to separate args with space and draw on top.. hacky.. yes.. this is becomes mre and more hacky and less and kess maninatable haha
       // ignoring defmap issues for now

     $car[$elsa]['content']=str_replace(" into ", " _10 ",$car[$elsa]['content']);
      foreach($car[$elsa]['words'] as &$anna){
        if($anna == "into")$anna="_10";
        }
      }*/
    
    render_elsa($car,$car[$elsa],$elsa);
    }
    

  $fds=$fds_copy;
  $flattened=999;
  $flat_car=$car;

  while(($elsa=fetch_next_elsa($fds))>=0){
    //if this is a new shallow depth, flatten it
    $elsa_depth=$car[$elsa]['depth'];
    if($elsa_depth<$flattened){
      flatten_parsing($flat_car,$elsa_depth);
      $flattened=$elsa_depth;
      }
    @render_brak($flat_car[$elsa]);
    }


  if($defmap)$defmap['html']=defmap_imap($flat_car[0]['defmap'],$flat_car[0]['height'],@$defmap['name'],@$defmap['href']);
  return $flat_car[0];
  }




function render_brak(&$elsa){
  if(!isset($elsa))return NULL;

  //its a braket, lets render it
  if(is_array($elsa['brak'])&&strlen($elsa['brak']['funk'])>0){
    
    $arg_split=",";
    $argc=$elsa['brak']['arg_count'];

/*
    switch($elsa['brak']['opts'][0][0]){
      case "convert":$arg_split=" into ";
            $argc=2;
            break;
      }
*/
    if($argc>1){


      $args=explode($arg_split,$elsa['content']);

      //specific brak custom code awfull practice I know
      //but we are already at the point where good practice and proper design is going out the window
      //full revamp is needed and we are getting close to a realtively full feautured system
      //so I'm gonna cut a few corners on this version and just know that im shorting life
      //how long this version will last, is how long it takes until a full revamp is in order, and every cut cortner like this will hasten that 
      switch($elsa['brak']['opts'][0][0]){
        case "foreach":
                       $args[0]="arel[".str_replace("as","] arel[",$args[0])."]";
                       break;
        case "rinteract":
                       $nargs0=array();
                       $nargs1=array();
                       if(strstr($args[0],"from")){
                         $nargs0=explode("from",$args[0]);
                         }elseif(strstr($args[0],"into")){
                         $nargs1=explode("into",$args[0]);
                         }else{
                         $nargs0[0]=$args[0];
                         }
                       if(strstr($args[1],"from")){
                         $nargs0=explode("from",$args[1]);
                         }elseif(strstr($args[1],"into")){
                         $nargs1=explode("into",$args[1]);
                         }else{
                         $nargs1[0]=$args[1];
                         }
                       //$nargs0=explode("from",$args[0]);
                       //$nargs1=explode("into",$args[0]);
                       //$nargs0=explode("from",$args[0]);
                       //$nargs1=explode("into",$args[1]);
                       $nargs=array( 
                                    @$nargs0[0],
                                    @$nargs0[1],
                                    @$nargs1[0],
                                    @$nargs1[1],
                                   );
                       $args=$nargs;
                       break;
        }
      

      $chunks=array();
      foreach($args as $arg){
        $chunks[]=pre_render_into_chunk($arg);
        }
      $chunks[0]['opts']=$elsa['brak']['opts'];
      $braketed=$elsa['brak']['funk'] ($chunks);
      }else{
      $braketed=$elsa['brak']['funk'] ($elsa);
      }
    $xos=@$braketed['brako_xstart'];
    $xoe=@$braketed['brako_xend'];
    $xcs=@$braketed['brakc_xstart'];
    $xce=@$braketed['brakc_xend'];
    if(@$braketed['defmap_reset'])$elsa['defmap']=array();
    if(@$braketed['defmap_set'])$elsa['defmap']=$braketed['defmap_set'];
    if($xoe&&$xcs&&$xce){
      $b1=create_dmap(@$braketed['brak']['spelling'],($xoe-$xos),$braketed['height']);

      defmap_direct_append($b1,$elsa['defmap'],$xoe);
      //ar_dump($elsa,"elsa");

      //changed because looked wrong.. need deeper analysis to be sure
      //$b2=create_dmap(@$elsa['brak']['spelling'],$xce-$xcs,$braketed['height']);
      $b2=create_dmap(@$braketed['brak']['spelling'],$xce-$xcs,$braketed['height']);

      defmap_direct_append($b1,$b2,$xcs);
      $elsa['defmap']=$b1; 
      }
    $elsa['svg']=$braketed['svg'];
    $elsa['width']=$braketed['width'];
    $elsa['height']=$braketed['height'];
    }
  $elsa['depth']--;

  return;
  }

function flatten_parsing(&$par,$depth){
  $first_elsa=-1;
  $arc=max(array_keys($par));
  for($i=0;$i<=$arc;$i++){
    $elsa=&$par[$i];
    if(!is_array($elsa))continue;

    if($elsa['depth']==$depth){
      if($first_elsa!=-1){
        //merge into the first elsa in this straight
        append_elsa($par[$first_elsa],$par[$i]);
        unset($par[$i]);

        }else{
      	$first_elsa=$i;
        }
      }else{
      $first_elsa=-1;	
      }
    }
  return;
  } 

//Iduna is Elsa's mom ( "Append Child to Parent" hehe :D )
function append_elsa(&$iduna,&$elsa,$spacing=9999){
  global $default_word_spacing,$defmap_on;

  if($spacing==9999){
    $spacing=$default_word_spacing;
    }

  $elsa_x=$iduna['width']+$spacing;

  if($defmap_on){
    //echo "doing defmap append";
    defmap_append($iduna,$elsa,$elsa_x);
    }

  $iduna['svg'].=draw_svg_symbol($elsa['svg'],0,$elsa_x);
  $iduna['width']=$elsa_x+$elsa['width'];
  $iduna['content'].=" ".$elsa['content'];

  if($elsa['height']>$iduna['height'])$iduna['height']=$elsa['height'];

  unset($elsa);
  return;
  }

function render_elsa(&$car,&$elsa,$elsa_id){

  if(is_array(@$elsa['words'])){
  	$txt_str="";
    foreach($elsa['words'] as $tword){
      $txt_str.=$tword." ";
      }
    $draw=draw_string($txt_str);
    $elsa['svg']=$draw['svg'];
    $elsa['width']=$draw['width'];
    $elsa['height']=$draw['height'];
    $elsa['defmap']=$draw['defmap'];
    $elsa['words_x']=$draw['words_x'];
    }
  
  return;
  }


function flatten_depth_sort($dsar){
  //let's treat this top level as an old school for loop, just to make sure the keys are orders because that is relevant here
  $far=array();
  $arc=count($dsar);
  for($i=0;$i<$arc;$i++){
    foreach($dsar[$i] as $elsa){
      $far[]=$elsa;
      }
    }
  return $far;
  }


function depth_sort($par){
  $sorted_index=array();
  
  $arc=count($par);
  for($i=0;$i<$arc;$i++){
  	$sd=@$par[$i]['depth'];
  	if(!isset($sd))continue;
    $sorted_index[$sd][]=$i;
    }

  return $sorted_index;
  }

function fetch_next_elsa(&$fds){
  $elsa=array_pop($fds);
  if(isset($elsa))return $elsa;
  return -1;
  }

?>