<?php

$notices=array();

function post_notice($notice){
  global $notices;
  $notices[]=$notice;
  return;
  }

function display_notices($notice_template=NULL){
  global $notices;
  $rstr="";
  if($notice_template){
    foreach($notices as $anna){
      $rstr.=str_replace("#notice#",$anna,$notice_template);
      }
    }else{
    foreach($notices as $anna){
      $rstr.=$anna."<br>";
      }
    }
  return $rstr;
  }


?>