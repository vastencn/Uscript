<?php
require_once("config.php");

update_def_from_post();

?>
<html>
<head>
 <title>def test</title>
 <?php echo uscript_head();?>
</head>
<body>

<?php
//echo "!!!";
//echo pre_render("a a a\n b b b \n c c c ");
//echo "!!!";

$istr=html_postget("istr");
echo "{1{$istr}}";
$istr=insert_pre_renders($istr);
echo "{2{$istr}}";
echo selfform("istr","post",100);



?>


<table>
	<tr>
	  <td>
<?php
$href="inspectedit_test.php?istr=".urlencode($istr)."&defs=";
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



      $vstr.=$tdef;
      $map_url="http://127.0.0.1/uscript/inspectedit_test.php?istr=".urlencode($istr)."&defs=$vstr,";
      $self_url="http://127.0.0.1/uscript/inspectedit_test.php?istr=".urlencode($istr)."&defs=$defs&defup=1";
      $vstr.=",";

      $def_html    =render_def($tdef,"width=600 border=0",$map_url);
      $defedit_html=render_defedit($tdef,"width=600 border=0",$self_url);

     echo "<table border=2 bordercolor=black><tr><td>$def_html <hr> $defedit_html </td></tr></table>";
    
     $used[]=$tdef;

		if($i>=10)break; //max ten defs
     }
  }

echo display_notices();
?>
    </td>
  </tr>
</table>

</body>
</html>
