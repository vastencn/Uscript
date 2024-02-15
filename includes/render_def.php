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

function render_defedit($word,$topts=NULL,$href=NULL){
  if(!$def=search_def($word))$def=empty_def($word);


  if(!$topts)$topts="border=0";

  $tdef=@$def['text'];

  $html="<table><tr valign=top><td align=right><form action=\"$href\" method=post>".
        "<textarea rows=15 cols=100 name=defuptext>".$def['raw']."</textarea><br>".
        "<input type=submit value=\"update ".$def['word']."\">".
        "<input type=hidden name=defupword value=\"".$def['word']."\">";
        "</form></td></tr></table>";

  return $html;
  }

?>