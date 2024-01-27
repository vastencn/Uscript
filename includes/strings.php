<?php

function space_split($instr){
  $rar=array();
  $sar=explode(" ",$instr);
  for($i=0;$i<count($sar);$i++){
    if(strlen($sar[$i])>0)$rar[]=$sar[$i];
    }
  return $rar;
  }

  function space_contract($instr){
    $sar=space_split($instr);
    return implode(' ',$sar);
    }

?>
	