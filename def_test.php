<html>
<head>
 <title>text test</title>
  
	<script type="text/javascript" src="js/cvi_tip_lib.js"></script>
	<script type="text/javascript" src="js/maputil.js"></script>
	<script type="text/javascript" src="js/mapper.js"></script>
</head>
<body>

<?php

if(@!$istr=$_POST['istr']){
  $istr="101 sub(101 sub( 10 01) 10) 111 000 sub ( 110011 )";
  $istr="010 (101)subof 111 ";
  }

?>
<div>
	<table>
<tr><td>



<form action=def_test.php method=post>
	<input type=text size=40 name=istr value="<?php echo $istr;?>">
	<input type=submit>
</form>

</td></tr>
<tr>
	<td>


<?php
require_once("config.php");


echo render_def("plus","width=600 border=1");


//  echo render_line_with_defmap($istr);
//  echo "</td></tr><tr><td>";
//  echo render_line_with_defmap("mult exp absolute");

?>
</td></tr></table>
</div>
</body>
</html>
