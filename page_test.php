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

<?php
$istr=html_postget("istr");
$istr=insert_pre_renders($istr);
//echo selfform("istr");

$page=NULL;
if($page_name)$page=load_page_struct($page_name);

if($page){

  //ar_dump($page);

  if(html_postget("update")){
    $nrows=html_postget("rows");
    $ncols=html_postget("cols");
    $desc=html_postget("desc");
    if($nrows>0&&$ncols>0&&$nrows<50&&$ncols<50){
      $page['row_count']=$nrows;
      $page['col_count']=$ncols;
      $page['desc']=$desc;
      page_fill_missing($page);
      page_save_struct($page);
      }
    }


    if($cell_edit){
      
      $cell_split=explode("_",$cell_edit);
      $cell_vars=array();
      $cell_vars['name']=$cell_split[0];
      $cell_vars['row']=$cell_split[1];
      $cell_vars['col']=$cell_split[2];
      $cell_desc=html_postget("celldesc_up");

      echo "((cell desc : $cell_desc))";
      if($cell_desc){
        echo "(($cell_edit : $cell_desc))";
        save_cell_desc($cell_edit,$cell_desc);
        }

      $row_op=html_postget("row");
      switch($row_op){
        case "m":
                 $page['rows'][$cell_vars['row']][$cell_vars['col']][0]-=1;
                 break;
        case "p":
                 $page['rows'][$cell_vars['row']][$cell_vars['col']][0]+=1;
                 break;
        }

      $row_op=html_postget("col");
      switch($row_op){
        case "m":
                 $page['rows'][$cell_vars['row']][$cell_vars['col']][1]-=1;
                 break;
        case "p":
                 $page['rows'][$cell_vars['row']][$cell_vars['col']][1]+=1;
                 break;
        }
      page_save_struct($page);
      }

    //$trow=$page['rows'][2];
    //$page['rows'][2]=$page['rows'][1];
    //$page['rows'][1]=$trow;
    ?>
<form action=<?php echo $_SERVER['PHP_SELF'];?>?update=1&page=<?php echo $page_name;?> method=post>
  rows: <input type=text name=rows size=4 value=<?php echo $page['row_count'];?>><br>
  cols: <input type=text name=cols size=4 value=<?php echo $page['col_count'];?>><br>
  desc: <textarea name=desc rows=6 cols=40><?php echo @$page['desc'];?></textarea><br>
  Fully defined: <textarea name=fully rows=6 cols=20><?php echo @$page['fully'];?></textarea><br>
  Rougly defined: <textarea name=roughly rows=6 cols=20><?php echo @$page['roughly'];?></textarea><br>
  Used, Not defined here: <textarea name=used rows=6 cols=20><?php echo @$page['used'];?></textarea>
  <input type=submit value="update page table"></form>

    <?php
    echo "<pre>".page_struct_gen_savefile($page)."</pre>";

    if(html_postget("up")){
      page_cell_update($cell_edit,html_postget("cell_content"));
      }


    $page_edit_link="page_test.php?page=$page_name&page_edit=1";
    $cell_edit_link=NULL;
    if(html_postget("page_edit")){
      $cell_edit_link=$page_edit_link."&cell_edit=";
      }

    $page_editor=render_page_edit($page,$cell_edit_link,$cell_edit);
    echo "<a href=$page_edit_link><font color=blue>edit page</font></a> - <a href=page_test.php?page=$page_name><font color=blue>edit off</font></a><hr>";
    echo $page_editor;

    //ar_dump($page);
    }

?>


<table>
	<tr>
	  <td>


	  </td>
	</tr>
	<tr>
	  <td>
<?php
/*
// can dd back in later to explore the defs in a page
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

//echo display_notices();
*/
?>
    </td>
  </tr>
</table>

<?php


$arendelle=dir_entries($pages_dir,".txt");
foreach($arendelle as $anna){
  $anna=str_replace(".txt","",$anna);
  echo "<a href=page_test.php?page=$anna>$anna</a><br>";
  }
?>
<hr>
<form action=<?php echo $_SERVER['PHP_SELF'];?>?newpage=1 method=post>
  name: <input type=text name=pname size=15><br>
  rows: <input type=text name=rows size=4><br>
  cols: <input type=text name=cols size=4><br>
  <input type=submit value="create new page">
</form>
</body>
</html>
