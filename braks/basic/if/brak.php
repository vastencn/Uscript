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
$brak_funk_name="if_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=2;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="if";

//a defintion of the brakets menaing
$brak_text_def =  "the IF braket\n";



function if_brak($chunks,$dot_radius=5,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  $yesno=1;
  $iff=FALSE;
  $opts=@$chunks[0]['opts'];
  if(@count($opts)>0){
    foreach($opts as $opt){
      $vname=$opt[0];
      $vval=$opt[1];
      switch($vname){
        case "no":
                   $yesno=0;
                   break;
        case "not":
                   $iff=TRUE;
                   break;
        }
      }

    }

  $ctxt="sub(".@$chunk['string'].")";
  $nchunk=create_chunk($ctxt);

  $subopts=array();
  if($yesno){
    $subopts[]=array("yes","1");
    }else{
    $subopts[]=array("no","1");
    }
  $chunks[1]['brak']['opts']=$subopts;

  $brakopts=array();
  if($iff){
    $brakopts[]=array("circ","1");
    }else{
    $brakopts[]=array("dot","1");
    }
  $chunks[0]['brak']['opts']=$brakopts;

  $condition=brak_brak($chunks[0]);  
  $action=subcup_brak($chunks[1]);
  append_elsa($condition,$action,-0);

  return $condition;
  }

?>