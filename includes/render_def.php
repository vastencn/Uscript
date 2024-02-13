<?php

function render_def($word,$topts=NULL){
  if(!$def=search_def($word))return NULL;

  if(!$topts)$topts="border=0";

  $udef=render_line_with_defmap(@$def['uscript']);
  $tdef=@$def['text'];

  $html="<table $topts><tr><td>";
  $html.=$udef;
  $html.="</td></tr><tr><td>";
  $html.=$tdef;
  $html."</td></tr></table>";

  return $html;
  }

?>