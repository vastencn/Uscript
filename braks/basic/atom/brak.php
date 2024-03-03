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
$brak_funk_name="atom_brak";

//arument delimiter
//NULL because this braket takes only one argument (should default to NULL, but setting it to be safe)
$brak_arg_delim=NULL;

//arument count
//1 because this braket takes only one argument (should default to 1, but setting it to be safe)
$brak_arg_count=1;

//OPTIONAL
//default spelling of the braket
$brak_default_spelling="atom";

//a defintion of the brakets menaing
$brak_text_def = 	"the atom brak is not actually a brak\n".
				  	"the attom brak is just a way to pass parameters to a symbol drawing function";



function atom_brak($chunk,$cup_depth=10,$hpad=3,$vpad=2,$stroke_width=2){
  $pstr=$chunk['content'];

  $par=explode(" ",trim($pstr));
  if(!ctype_digit($par[0]))return NULL;  //should probably default to retur the un-annoted general symbol for atom

  $atom_num=$par[0];
  if($atom_num<1)return NULL;
  
  $pc=count($par);
  $isotope=0;
  if($pc>1&&is_numeric($par[1])){
    $isotope=round($par[1]);
    if($par[1]==="0")$isotope="0";
    if($par[1]==".")$isotope=".";
    }


  $ion=NULL;
  if($pc>2&&is_numeric($par[2])){
    $ion=round($par[2]);
    if($par[2]==="0")$ion="0";
    }

  $anum_str=dec_digit_or_bstr($atom_num);
  $iso_str=dec_digit_or_bstr(abs($isotope));
  if($isotope==="0")$iso_str="0";
  if($isotope==".")$iso_str="dot";
  if($ion)$ion_str=dec_digit_or_bstr(abs($ion));
  if($ion==="0")$ion_str="0";

  $top_text_height=6;
  $bot_text_height=6;
  $total_height=25;

  $bot_text_start=$total_height-$bot_text_height;

  if($isotope<0)$iso_str="inv ".$iso_str;
  if($ion<0)$ion_str="false ".$ion_str;


  $elsa=NULL;
  if($iso_str||$ion||$iso_str==="0"||$ion_str==="0"){
    if($isotope||$iso_str==="0"){$bot_str=$iso_str;}else{if($ion>0)$bot_str="dot ";}
    if($ion||$ion_str==="0")$bot_str.=" ".$ion_str;
    $elsa=scale_to_height(render_uscript_text($bot_str),$bot_text_height);
    }

  $atom_height=$total_height-$top_text_height;
  if($bot_str)$atom_height-=$bot_text_height;



  $rows=array();
  $rows['anum']=scale_to_height(render_uscript_text($anum_str),$top_text_height);
  $rows['atom']=scale_to_height(search_char("atom"), $atom_height);
  if($elsa)$rows['bot']=scale_to_height( 
                                          render_uscript_text($bot_str),
                                          $bot_text_height 
                                        );

  vuncenter_lines($rows);
  hcenter_lines($rows);
  vstack_lines($rows,1);

  $nchunk=fuse_chunks($rows);
  vcenter($nchunk );
  $nchunk['string']="atom($pstr)";  
  $nchunk['drawn']=TRUE;
  $nchunk['brako_xstart']=0;
  $nchunk['brako_xend']=$nchunk['width'];
  $nchunk['brakc_xstart']=$nchunk['width'];
  $nchunk['brakc_xend']=$nchunk['width'];
  $nchunk['defmap_reset']=TRUE;
  return $nchunk;
  }



?>