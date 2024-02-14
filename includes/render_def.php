<?php

function render_def($word,$topts=NULL,$href=NULL){
  if(!$def=search_def($word))return NULL;



  if(!$topts)$topts="border=0";

  $udefs=array();
  foreach(@$def['uscript'] as $dline){
    $udefs[]=render_line_with_defmap($dline,NULL,$href);
    }
  
  $tdef=@$def['text'];

  $html="<table><tr valign=top><td align=center bgcolor=#eeeeee>";
  $char=render_line($word);

  $html.=$char;
  $html.="<br>$word";
  $html.="</td><td>";

  $html.="<table $topts><tr><td>";

  foreach($udefs as $line){
    $html.=$line;
    $html.="</td></tr><tr><td>";
    }
  $html.="</td></tr><tr><td>";
  $html.=$tdef;
  $html."</td></tr></table>";

  $html.="</td></tr></table>";

  return $html;
  }

?>