<?php
require_once("config.php");

update_def_from_post();


$page_name=html_postget("page");
$cell_edit=html_postget("cell_edit");

if(html_postget("newpage")){
  $npname=html_postget("pname");
  if($new_page=new_page_struct($npname,html_postget("rows"),html_postget("cols"))){
    page_save_struct($new_page);
    $page_name=$npname;
    }
  }

?>
<html>
<head>
 <title>def test</title>
 <?php echo uscript_head();?>
</head>
<body>
<a href=pages_index.html>Back to index</a>
<hr><hr>
Page Contents (<?php echo "$page_name"; ?>)<br><br><br>
<?php
$istr=html_postget("istr");
$istr=insert_pre_renders($istr);
//echo selfform("istr");

$page=NULL;
if($page_name)$page=load_page_struct($page_name);

if($page){

    ?>
  </td>
 </tr>
</table>

    <?php
    //echo "<pre>".page_struct_gen_savefile($page)."</pre>";


    $page_edit_link="page_test.php?page=$page_name&page_edit=1";
    $cell_edit_link=NULL;
    if(html_postget("page_edit")){
      $cell_edit_link=$page_edit_link."&cell_edit=";
      }

    $page_editor=render_page_edit($page,$cell_edit_link);
    //echo "<a href=$page_edit_link><font color=blue>edit page</font></a> - <a href=page_test.php?page=$page_name><font color=blue>edit off</font></a><hr>";
    echo $page_editor;
    echo "<br><br><br><hr><hr><br>new chars/structures on this page<br><br>";
    page_defs($page_name);
    }

?>



<br><br><br><hr><hr><br>full page desc content<br><br>
  <?php echo @$page['desc'];?>

</body>
</html>
