<?php
require_once("config.php");

update_def_from_post();

$vall=@$_GET['all'];



$page_name=html_postget("page");
$cell_edit=html_postget("cell_edit");

?>
<html>
<head>
 <title>def test</title>
 <?php echo uscript_head();?>
</head>
<body>

<table border=0><tr><td colspan=2><font size=4>This is a flat version<br>There is a full interactive UI for designing pages<br>There is a markup language, image draing system, modular library of symbols, modular bracketing system, etc..<br>But I don't think it's web-safe. Too many file operations (create, delete, edit functions lal over). <br>It's probably exploitable by bad actords so I only post a flat versio on my website<br><a hrewf=https://github.com/vastencn/Uscript><https://github.com/vastencn/Uscript</a><br>documentiation minimal and missing chuncks. Feel fre to ask me if you have any questions m@dscript.org</td></tr><tr><td><img src=img/ui_001.jpg></td><td><img src=img/ui_002.jpg></td></tr><table>


Yes.. there are <b>still</b> , even after so so so many passes, additions, edits, and revision happening as this is made.
<br><br>
The flow of definitions shows it, there are still, even now, definitions that come after 'their group' and other definitions that would benefit from being shifted backwards so they can use other definitions that exist later.
<br><br>
But I am finally satisfied and confident that this version and these tools can come together into a solid enough framework that I can move on to the 'expansion plug-in' I have been holding onto.
I have been getting impatient, I don't want to start adding plugins to a framework that hasn't even been well defined itself. But I think this version will be able to flesh out the math, procedural, and physics base solidly... plus move on to more communicative and abstracted layers and define them all with a strong enough base to justify adding 'plug-ins'. Now I just need to keep picking at it until it gets there.... big project, must not rush or get impatient hehe.
<br><br>
General flow is
<ol>
<li><b>Math</b><br>
  Obvious first step. numbers and basic arithmetic are super easy to define visually from scratch
  <li><b>Procedural</b><br>
  With math it is then easy to define sequence of operations, loops, brackets, arrays, etc..
  <li><b>Geometry</b><br>
  Geometry is easily defined using some constants like pi, equations like a²+b²=c², functions like sin(), cos(), etc..
  <li><b>Calculus</b><br>
  Using functions and arrays we can use geometric concepts to define slopes, areas under curves, etc..
  <li><b>Physics</b><br>
   Starting with fundamental particles, a table of mass values is universal (not in unit values, units are arbitrary... but ratios between the particles is universal). With particles we can then define units like distance, time, forces etc.. as well as molecules, interactions, etc..
  <li><b>Astronomy</b><br>
    Using physics we can define the other side of the universal spectrum. The very small is universal (particles, forces, etc...) and the very large is also universal (Astronomy). So we can define stars, planets, blackholes, galaxies, etc... (funnily, what is not universal is all the things that seem most significant in our lives, the human scale is not universal.  I suppose we find ourselves in this range precisely because this is where permutations and possibilities are so vast.)
  <li><b>Communication and Abstraction</b><br>
  At this point move on to building communication tools for exchanging ideas, asking questions, etc... The building blocks established in previous layers can be used directly, or as abstractions. Establish ways to abstract with metaphor, analogy, etc... We can create things akin to 'x is like y', 'x is y-ish' etc...
  <li><b>Plug-ins</b><br>
  Plug in other universal definition from visual example. Add other methods for defining from scratch.
</ol>
<pre>
I have started trying to properly mentally adopt base 16 so I can use it more fluently in designing Uscript.

I started using a new finger counting system, simply make thumbs worth 4, so that I can count to 16 on my fingers
(considering how many coders and engineers have worked with and learned hex, someone else must have already done this, its too obvious)

I also find using decimal numbers verbally like one,two,three are very confusing for hex
so I designed a simple way to vocalize the binary glyphs
I(eye) is a higher frequency than A(ahh) so I make the higher frequency the higher value (entropy/redshift/low-value association is consistent with the rest of  uscript)

I also use a simple visualization technique for numbers, numbers are 4 square groups of 4 dot squares.
The "full blocks of 4 points" are separated from the "unfinished dot block" so it feels more like "14 is 3 blocks and 2 dots"
I find this quite easy to visualize and use in working memory.
</pre>
<table border=0><tr><td><img src=img/nums_001.jpg></td><td><img src=img/nums_002.jpg></td></tr></table>
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
  <table border=0>
    <tr valign=top>
      <td>
  rows: <input type=text name=rows size=4 value=<?php echo $page['row_count'];?>><br>
  cols: <input type=text name=cols size=4 value=<?php echo $page['col_count'];?>><br>
  desc: <textarea name=desc rows=6 cols=40><?php echo @$page['desc'];?></textarea><br>
  Fully defined: <textarea name=fully rows=6 cols=20><?php echo @$page['fully'];?></textarea><br>
  Rougly defined: <textarea name=roughly rows=6 cols=20><?php echo @$page['roughly'];?></textarea><br>
  Used, Not defined here: <textarea name=used rows=6 cols=20><?php echo @$page['used'];?></textarea>
 </td>
  <td>
    <?php
    $pnum=explode("page",$page_name);
    if(count($pnum)>1&&is_numeric($pnum[1])){
      echo "<a href=".$_SERVER['PHP_SELF']."?show_prev=".$pnum[1]."&page=$page_name>Show all preceeding</a>";
      }
    ?>
    
    <?php
    $prev_pages=html_postget("show_prev");
    if($prev_pages){
      for($i=1;$i<$prev_pages;$i++){
        echo "Page $i<br>";
        page_defs("page".$i);
        echo "<hr>";
        }

    }

    page_defs($page_name);
/*
    $showar=explode("*show*",@$page['desc']);
    if(count($showar)>1){
      $sar_lines=explode("\n",$showar[1]);
      foreach($sar_lines as $tline){
        $tln=trim($tline);
        if(strlen($tln)>0){
          $ln_els=explode(",",$tln);
          echo "<table border=1 bordercolor=black bgcolor=black cellpadding=4s><tr>";
          foreach($ln_els as $tel){
            $tel=trim($tel);
            $elar=explode(" :: ",$tel);
            echo "<td bgcolor=white>".render_line(str_replace("..",",",$elar[0]))."  ".@$elar[1]."</td>";
            }
          echo "</tr></table><br>";
          }
        }

      }
*/
    ?>
  </td>
 </tr>
</table>
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

<table>
  <tr>
    <td bgcolor=#000000><font color=#fffffff>Page</font></td>
    <td bgcolor=#000000><font color=#fffffff>defs</font></td>
    <td bgcolor=#000000><font color=#fffffff>desc</font></td>
    <td bgcolor=#000000><font color=#fffffff>todo</font></td>
  </tr>
<?php








//******************************************Flattener


$pagedump=file($http_base."pages_index_flat.php");

file_dump( $pages_flat_dir."index.html",implode($pagedump));










$arendelle=dir_entries($pages_dir,".txt");
$pagelistid=1;
$found=true;
while($found){
  $found=false;
  foreach($arendelle as $anna){
    $pagename="page$pagelistid";
    if($anna=="$pagename.txt"){
      
      if($pagelistid%2==0){
        $bgc="#eeeeee";
        }else{
        $bgc="#cccccc";
        }
      $page=load_page_struct($pagename);
      $pagedump=file($http_base."page_test_flat.php?page=$pagename");

      file_dump( $pages_flat_dir."$pagename.html",implode($pagedump));
      echo "<tr valign=top bgcolor=$bgc><td><a href=$pagename.html><font color=blue>Page $pagelistid</font></a></td>";      
      echo "<td>";
      page_defs($pagename);
      echo "</td>";
      $todo="";
      $desc=strstr($page['desc'],"*show*",true);

      $dsplit=explode("<todo>", $desc);
      if(count($dsplit)>1){
        $desc=$dsplit[0];
        for($i=1;$i<count($dsplit);$i++){
          $nsplit=explode("</todo>",$dsplit[$i]);
          $todo.=$nsplit[0];
          $desc.=$nsplit[1];
          }
        }
      $desc=str_replace("\n", "<br>",htmlspecialchars( $desc));
      $todo=str_replace(" img_","\">",str_replace("_img ","<img src=\"img/",str_replace("\n", "<br>",htmlspecialchars( $todo))));
      echo "<td width=300><font size=1>$desc</font></td>";
      echo "<td width=300><font size=1>$todo</font></td>";
      echo "</tr>";


      if($vall){
        echo "<td colspan=3>";
        echo render_page_edit($page,"","");
        echo "</td>";}

      $found=true;
      }
    }
  $pagelistid++;
  }

?>

</body>
</html>
