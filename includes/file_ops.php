<?php
function file_dup($from_file,$to_file){
  $bdat=@implode(@file($from_file));
  if(strlen($bdat)<1)return NULL;
  if(!$fp=@fopen($to_file,"w"))return NULL;
  fwrite($fp, $bdat);
  fclose($fp);
  return TRUE;
  }

function file_dump($fpath,$fdat){
  if(!$fp=@fopen($fpath,"w"))return NULL;
  fwrite($fp, $fdat);
  fclose($fp);
  return TRUE;
  }
?>