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
  <?php
  }

$presaves = scandir($presave_dir);
foreach($presaves as $ps){
  if(strlen($ps)<3)continue;
  $elsa=explode(".",$ps);
  if(count($elsa)!=2)continue;
  if($elsa[1]!="txt")continue;
  echo "<a href=presave_editor.php?edit=".$elsa[0]."><font color=blue size=1>".$elsa[0]."</font></a><br>";
  }



?>

    </td>
  </tr>
</table>

</body>
</html>
