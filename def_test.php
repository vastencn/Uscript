<?php
require_once("config.php");
?>

<html>
<head>
 <title>def test</title>
<?php echo uscript_head();?>
</head>
<body>

<?php

if(@!$def=$_GET['def']){
  $def="plus";
  }
if(@$_POST['def']){
  $def=$_POST['def'];
  }

?>
<div>
	<table>
<tr><td>



<form action=def_test.php method=post>
	<input type=text size=40 name=def value="<?php echo $def;?>">
	<input type=submit>
</form>

</td></tr>
<tr>
	<td>


<?php


$def=render_def($def,"width=600 border=0","http://127.0.0.1/uscript/def_test.php?def=");

echo "<table border=2 bordercolor=black><tr><td>$def</td></tr></table>";

//  echo render_line_with_defmap($istr);
//  echo "</td></tr><tr><td>";
//  echo render_line_with_defmap("mult exp absolute");

?>
</td></tr></table>
</div>
</body>
</html>
