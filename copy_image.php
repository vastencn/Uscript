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

$ename="imagecopier";

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
  $http_path=$http_presave_dir.$ename;

  ?>
  <form action=copy_image.php?edit=<?php echo $ename;?>&up=1 method=post>
  <table>
  	<tr>
  		<td><img src=<?php echo $http_path;?>.svg></td>
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
?>



</body>
</html>
