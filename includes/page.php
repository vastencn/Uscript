<?php
require_once("config.php");

function fecth_cell($cell_name){
	global $pages_cells_dir;

  $fname=$pages_cells_dir.$cell_name.".txt";
  if(!$cell_dat=@file($fname))return "empty";
  return implode($cell_dat);
  }

function page_struct_gen_savefile($page){

  $rows_new=array();
  $rows_new[]=$page['row_count'].",".$page['col_count'];
	foreach($page['rows'] as $row){

    $cols_new=array();
		foreach($row as $col){
      $cols_new[]=implode(":",$col);
		  } 
    $rows_new[]=implode(",",$cols_new);
	  }
  $save_file=implode("\n",$rows_new);

  return $save_file;
  }

function page_save_struct($pname,$page){
	global $pages_dir;
  $fname=$pages_dir.$pname.".txt";
  $struct_dat=page_struct_gen_savefile($page);
  file_dump($fname,$struct_dat);
  return;
  }


function load_page_struct($pname){
	global $pages_dir;
  if(!$fdat=file($pages_dir.$pname.".txt"))return NULL;
  array_walk($fdat,'rm_nl_ptr');

  $hline=explode(",",$fdat[0]);
  if(count($hline)<2)return NULL;

  $page['name']=$pname;
  $page['row_count']=$hline[0];
  $page['col_count']=$hline[1];
  $page['rows']=array();
  $fdat[0]=NULL;

  $row=-1;

  foreach($fdat as $line){
  	if(++$row>$page['row_count'])continue;
  	if(!$line)continue;
    $cols=explode(",",$line);
    $col=0;
    foreach($cols as $colum){
    	$col++;
      $col_specs=explode(":",$colum);
      $page['rows'][($row)][$col]=$col_specs;
      }
    }

  return $page;
  }

function render_page_edit($page,$edit_link=NULL,$cell_edit=NULL,$border=1,$padding=2){
  
  $colspan=0;
  $rowspan=array();
  $edit=html_postget("cell_edit");

  $html="<table border=$border cellpadding=$padding cellspacing=0>";

  for($i=0;$i<$page['row_count'];$i++){

    $html.="<tr>";
    for($j=0;$j<$page['col_count'];$j++){
       
      $cell_name=$page['name']."_".$i."_".$j;
      $cell_dat=fecth_cell($cell_name);

      $html.="<td>";
      if($edit_link){
        $html.="<a href=$edit_link$cell_name><font size=1 color=blue>edit cell</font></a><br>";
        $html.="row<a href=$edit_link$cell_name&row=p><font size=1 color=blue>++</font></a> <a href=$edit_link$cell_name&row=m><font size=1 color=blue>--</font></a><br>";
        $html.="$cell_name<hr>";
        }

      $html.=multi_line_render($cell_dat);
      if($cell_edit==$cell_name){
        $html.="<form action=$edit_link$cell_name&up=1 method=post>";
        $html.="<textarea name=cell_content rows=10 cols=40>$cell_dat</textarea>";
        $html.="<input type=submit>";        
        $html.="</form>";
        }

      $html.="</td>";
      }
    $html.="</tr>";
    }
  $html.="</table>";
  return $html;
  }

function page_cell_update($cell_name,$cell_content){
	global $pages_cells_dir;

  $fname=$pages_cells_dir.$cell_name.".txt";
  file_dump($fname,$cell_content);
	return;
  }

?>

