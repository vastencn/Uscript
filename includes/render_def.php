<?php


function  def_render_prep($arendelle){
  $anna=implode("#NL#",$arendelle);
  $anna=insert_pre_renders($anna);
  $arendelle=explode("#NL#",$anna);
  $arendelle=def_preparse($arendelle);
  return $arendelle;
  }

function def_preparse($arendelle){
  //I often use elsa for element, most often for elements that are arrays
  //anna more often a string like element
  //elsa is complex with with her components "concealed inside"
  //anna's content is right on the surface for all to see
  // :D
  $arendelle=array_map('nltrim',$arendelle);
  $anna=implode("#NL#",$arendelle);
  $anna=str_replace("/n#NL#","",$anna);
  $arendelle=explode("#NL#",$anna);
  return $arendelle;
  }

function render_def($word,$topts=NULL,$href=NULL){

  $icon=TRUE;

  if(substr($word,0,14)=="imgrender_rimg"){
    global $render_dir;
    $iname=substr($word,14);
    $itxt=@file($render_dir."rtxt".$iname.".txt");
    $def=array();
    $def['uscript']=$itxt;
    $def['text']="  ";
    $icon=NULL;
    //return NULL;
    }elseif(substr($word,0,11)=="imgpresave_"){
    global $presave_dir;
    $iname=substr($word,11);
    $itxt=@file($presave_dir.$iname.".txt");
    $def=array();
    $def['uscript']=$itxt;
    $def['text']=" ";
    $icon=NULL;
    }else{

    if(!$def=search_def($word))return NULL;

    }



  if(!$topts)$topts="border=0";

  //$anna=implode("#NL#",@$def['uscript']);
  //$anna=insert_pre_renders($anna);
  //$arendelle=explode("#NL#",$anna);

  //$preparsed=def_preparse($arendelle);
  $preparsed=def_render_prep(@$def['uscript']);
  foreach(@$preparsed as $dline){
    $udefs[]=render_line_with_defmap($dline,NULL,$href);
    }
  
  $tdef=@$def['text'];

  $html="<table><tr valign=top><td align=center bgcolor=#eeeeee>";
  $char=render_line($word);

  if($icon)$html.=$char;
  $html.="<br>$word";
  $html.="</td><td>";

  $html.="<table $topts><tr><td>";

  foreach($udefs as $line){
    $html.=$line;
    $html.="</td></tr><tr><td>";
    }
  $html.="</td></tr><tr><td>";
  $html.=$tdef;
  $html.="</td></tr></table>";

  $html.="</td></tr></table>";
  //$html.="</td></tr></table>";

  return $html;
  }

function render_defedit($word,$topts=NULL,$href=NULL){
  if(!$def=search_def($word))$def=empty_def($word);

  $render_html="";
  if($path=has_prefix("img",$word)){
    $par=explode("_",$path);

    //if its a pre-render
    if(@$par[0]=="render"){
      $render_html.="<form action=\"$href\" method=post>".
                      "<input type=hidden name=rendersave value=\"1\">".
                      "<input type=hidden name=oname value=\"".str_replace("rimg","",$par[1])."\">".
                      "<tr><td align=center>".
                      "<input type=text name=\"savename\" value=\"".@$par[1]."\">".
                      "<input type=submit value=\"save this prerender\">".
                      "</td></tr>".
                    "</form>";
      }


    } 

  if(!$topts)$topts="border=0";

  $tdef=@$def['text'];

  $html="<table> $render_html <tr valign=top><td align=right><form action=\"$href\" method=post>".
        "<textarea rows=15 cols=100 name=defuptext>".$def['raw']."</textarea><br>".
        "<input type=submit value=\"update ".$def['word']."\">".
        "<input type=hidden name=defupword value=\"".$def['word']."\">".
        "</form></td></tr></table>";

  return $html;
  }

?>