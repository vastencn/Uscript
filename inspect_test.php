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
$istr=html_postget("istr");
echo selfform("istr");
?>


<table>
	<tr>
	  <td>
<?php
$href="inspect_test.php?istr=".urlencode($istr)."&defs=";
echo render_line_with_defmap($istr,NULL,$href);
?>

	  </td>
	</tr>
	<tr>
	  <td>
<?php

$i=0;
$defs=html_getpost("defs");
$vstr="";
$used=array();
if($defs){
	$dar=explode(",",$defs);
	foreach($dar as $tdef){
    if(in_array($tdef,$used))continue;
    if($i++)echo "<hr>";

    $vstr.=$tdef.",";
    $def_html=render_def($tdef,"width=600 border=0","http://127.0.0.1/uscript/inspect_test.php?istr=".urlencode($istr)."&defs=$vstr");
    echo "<table border=2 bordercolor=black><tr><td>$def_html</td></tr></table>";
    
    $used[]=$tdef;

		if($i>=10)break; //max ten defs
    }
  }

?>
    </td>
  </tr>
</table>

</body>
</html>
