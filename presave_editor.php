<?php
require_once("config.php");
?>
<html>
<head>
 <title>def test</title>
 <?php echo uscript_head();?>
</head>
<body>
<form action=presave_editor.php?new=1 method=post>
<table>
	<tr>
		<td>
			New presave name<input type=text size=15 name=nname><input type=submit value="create new">
		</td>
	</tr>
</table>
</form>

<?php

if(@$_GET['new']){
  $ename=$_POST['nname'];
  $path=$presave_dir.$ename;
  $tpath=$path.".txt";
  file_dump($tpath," ");
  }else{
  $ename=@$_GET['edit'];
  }

if( $ename && safe_fname($ename) && strlen($ename)>1 ){
  $path=$presave_dir.$ename;
  $tpath=$path.".txt";
  if(@$_GET['up']){
    $anna=$_POST['econtent'];
    file_dump($tpath,$anna);
    }

  $path=$presave_dir.$ename;
  $anna=implode("",file($tpath));
  $img=render_presave($ename);
  ?>
  <form action=presave_editor.php?edit=<?php echo $ename;?>&up=1 method=post>
  <table>
  	<tr>
  		<td><?php echo $img;?></td>
  	</tr>
  	<tr>
  		<td>Edit <?php echo $ename?></td>
  	</tr>
  	<tr>
  		<td>
  			<textarea name=econtent rows=10 cols=50><?php echo $anna;?></textarea>
  		</td>
  	</tr>
  	<tr>
  		<td><input type=submit></td>
  	</tr>
  </table>
</form></form>
<font size=1>
  <?php
  }




$presaves = scandir($presave_dir);
foreach($presaves as $ps){
  if($ps=="presave_index.txt")continue;
  if(strlen($ps)<3)continue;
  $elsa=explode(".",$ps);
  if(count($elsa)!=2)continue;
  if($elsa[1]!="txt")continue;
  echo "<a href=presave_editor.php?edit=".$elsa[0]."><font color=blue>".$elsa[0]."</font></a> - imgpresave_".$elsa[0]."<br>";
  }

$irecs=array();
$index=file($presave_dir."presave_index.txt");
foreach($index as $rec){
  $recdat=explode(",",$rec);
  $irecs[]=$recdat;
  }


$istruct=array();
$index=file($presave_dir."presave_struct.txt");

foreach($index as $rec){
  $car=str_split($rec);
  $depth=1;
  foreach( $car as $ch ){
    if($ch!=" ")break;
    $depth++;
    }
  $rec=trim($rec);
  $istruct[]=array(preg_replace("/[^a-zA-Z0-9]/", "", $rec),$depth);
  }

$depth=0;
foreach($istruct as $cat){
  if($cat[1]>$depth){
    while($cat[1]>$depth){
      echo "<ul>";
      $depth++;
      }
    }
  if($cat[1]<$depth){
    while($cat[1]<$depth){
      echo "</ul>";
      $depth--;
      }
    }
  echo "<li>".$cat[0];

  $slist=NULL;
  foreach($irecs as $item){
    if($item[0]==$cat[0]){
      $iname=preg_replace("/[^a-zA-Z0-9_]/", "", $item[1]);
      $slist.="<li><a href=presave_editor.php?edit=$iname>$iname</a>";
      }
    }
  if($slist){
    echo "<ol>$slist</ol>";
    }

  }
  
  while($depth>0){
    echo "</ul>";
    $depth--;
    }
?>

    </td>
  </tr>
</table>

</body>
</html>
