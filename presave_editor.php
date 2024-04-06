<?php
require_once("config.php");


if(@$_GET['cat_up']&&strlen($_POST['cat_dat'])>10){
  file_dump($presave_dir."presave_struct.txt",$_POST['cat_dat']);
  }


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

    $ncat=@$_POST['ncat'];
    if($ncat&&strlen($ncat)>2){
      file_append_line($presave_dir."presave_index.txt",$ncat.",".$ename);
      }
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
      <td>Add cat <input type=text name=ncat size=10></td>
    </tr>
  	<tr>
  		<td><input type=submit></td>
  	</tr>
  </table>
</form></form>
<font size=1>
  <?php
  }


$irecs=array();
$index=file($presave_dir."presave_index.txt");
foreach($index as $rec){
  $recdat=explode(",",$rec);
  $recdat[1]=preg_replace("/[^a-zA-Z0-9_]/", "", @$recdat[1]);
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
  $istruct[]=array(preg_replace("/[^a-zA-Z0-9 ]/", "", $rec),$depth);
  }

$depth=0;
$cat_chain=array();
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
      $slist.="<font size=1><li><a href=presave_editor.php?edit=$iname>$iname</a> - ips_$iname</font>";
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



echo "<table border=1 cellpadding=2 cellspacing=0>";

$presaves = scandir($presave_dir);
foreach($presaves as $ps){
  if(substr($ps,0,7)=="presave")continue;
  if(strlen(preg_replace("/[^a-zA-Z0-9_]/", "", $ps))<3)continue;
  $elsa=explode(".",$ps);
  if(count($elsa)!=2)continue;
  if($elsa[1]!="txt")continue;

  $catlist=NULL;

  foreach($irecs as $item){
    if($item[1]==$elsa[0]){
      $catlist.="<li><font size=1>".$item[0];
      }
    }
  if($catlist)$catlist="<ul>".$catlist."</ul>";

  echo "<tr valign=top><td><font size=1><a href=presave_editor.php?edit=".$elsa[0]."><font color=blue>".$elsa[0]."</font></a></td><td><font size=1>ips_".$elsa[0]."</td><td>$catlist</td></tr>";
  }

echo "</table>";

?>

    </td>
  </tr>
</table>

<?php


?>
<form action=presave_editor.php?cat_up=1 method=post>
  <textarea rows=8 cols=25 name=cat_dat><?php

echo implode(file($presave_dir."presave_struct.txt"));

?></textarea>
  <br>
  <input type=submit>
</form>

</body>
</html>
