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
$istr=html_postget("istr");
$istr=insert_pre_renders($istr);
//echo selfform("istr");

$page_name="test";

if($page_name){

    if($row_op=html_postget("istr")){
      html_postget("cell_edit");
      switch($row_op){
        case "m":
                 break;
        }
      }

    $page=load_page_struct($page_name);
    $trow=$page['rows'][2];
    $page['rows'][2]=$page['rows'][1];
    $page['rows'][1]=$trow;
    page_save_struct($page_name,$page);
    echo "<pre>".page_struct_gen_savefile($page)."</pre>";

    if(html_postget("up")){
      page_cell_update(html_postget("cell_edit"),html_postget("cell_content"));
      }


    $page_edit_link="page_test.php?page=$page_name&page_edit=1";
    $cell_edit_link=NULL;
    if(html_postget("page_edit")){
      $cell_edit_link=$page_edit_link."&cell_edit=";
      }

    $page_editor=render_page_edit($page,$cell_edit_link,html_postget("cell_edit"));
    echo "<a href=$page_edit_link><font color=blue>edit page</font></a> - <a href=page_test.php><font color=blue>edit off</font></a><hr>";
    echo $page_editor;

    ar_dump($page);
    }

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
