<?php

function html_getpost($vname,$def=NULL){
  global $_GET,$_POST;
  if(@$_GET[$vname])return $_GET[$vname];
  if(@$_POST[$vname])return $_POST[$vname];
  return $def;
  }


function html_postget($vname,$def=NULL){
  global $_GET,$_POST;
  if(@$_POST[$vname])return $_POST[$vname];
  if(@$_GET[$vname])return $_GET[$vname];
  return $def;
  }

function selfform($vars,$method="post",$len=20){
  global $_SERVER;
  echo "(($len)))";
  $self=htmlentities($_SERVER['PHP_SELF']);
  if(!is_array($vars))$vars=array($vars);

  $rstr="\n".
        "  <form action=\"$self\" method=\"$method\">\n".
        "    <table border=0>\n";
        "      <tr>\n";

  foreach($vars as $var){
  	$vval=html_postget($var);
    $rstr.="        <td>$var</td>\n";
    $rstr.="        <td><input type=text size=$len name=$var value=\"$vval\"></td>\n";
    }  
  $rstr.="      </tr>\n".
         "      <tr>\n".
         "        <td colspan=2 align=right><input type=submit></td>\n".
         "      </tr>\n".
         "    </table>\n".
         "  </form>\n\n";

  return $rstr;
  }

?>