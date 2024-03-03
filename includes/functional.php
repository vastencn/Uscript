<?php
$la=array();

function fna(&$arg,$i=0){
  global $la;
  if($arg)
      $la[$i]=&$arg;
  	else 
  	  $arg=@$la[$i];
  return;
  }

function fnr(){
  global $la;
  $la=array();
  }

function last_arg($i=0){
  global $la;
  return @$la[$i];
  }

function set_arg($arg,$i=0){
  global $la;
  $la[$i]=$arg;
  return $arg;
  }

function reset_arg($arg,$i=0){
  global $la;
  $la=array();
  $la[$i]=$arg;
  return $arg;
  }

function reset_args(){
  global $la;
  $la=array();
  return;
  }

?>