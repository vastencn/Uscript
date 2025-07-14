<?php
require_once("config.php");

function fecth_cell($cell_name){
	global $pages_cells_dir;

  $fname=$pages_cells_dir.$cell_name.".txt";
  if(!$anna=@file($fname))return array("empty","empty");


  $elsa=def_render_prep($anna);
  $sisters=array();
  $sisters['raw']=implode($anna);
  $sisters['draw']=implode("\n",$elsa);
  return $sisters;
  }

function page_fill_missing(&$page){
  for($i=0;$i<$page['row_count'];$i++){
    for($j=0;$j<$page['col_count'];$j++){
      if(!@$page['rows'][$i][$j])$page['rows'][$i][$j]=array(1,1);
      }
    }
  return;
  }

function page_desc($page){
	global $pages_dir;
	if(!$page||!@$page['name'])return NULL;
	if(!safe_fname(@$page['name']))return NULL;
  if(!$fdat=@file($pages_dir.$page['name']."_desc.txt"))return "";
  $anna=implode($fdat);
  return $anna;
  }


function new_page_struct($name,$rows,$cols){
	if(!safe_fname($name))return NULL;
	if($rows<1||$cols<1||$rows>50||$cols>50)return NULL;
  $elsa=array();
  $elsa['name']=$name;
  $elsa['row_count']=$rows;
  $elsa['col_count']=$cols;
  $nrows=array();
  for($i=0;$i<$rows;$i++){
    $nrow=array();
    for($j=0;$j<$cols;$j++){
      $nrow[]=array(1,1);
      }
    $nrows[]=$nrow;
    }
  $elsa['rows']=$nrows;
  return $elsa;
  }

function page_struct_gen_savefile($page){
  if(!$page)return NULL;
  if(!safe_fname($page['name']))return NULL;
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

function page_save_struct($page){
	global $pages_dir;
  $fname=$pages_dir.$page['name'].".txt";
  if(!$struct_dat=page_struct_gen_savefile($page))return NULL;
  file_dump($fname,$struct_dat);
  //ar_dump($page);
  if(!@$page['desc'])return;
  $fname=$pages_dir.$page['name']."_desc.txt";
  file_dump($fname,$page['desc']);
  return;
  }

function load_page_word_list($fname){
	global $pages_words_dir;
	$fpath=$pages_words_dir.$fname;
  //echo "((Loading page word list $fpath))";
  return NULL;
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
  $page['desc']=page_desc($page);
  $fdat[0]=NULL;

  $row=-1;

  foreach($fdat as $line){
  	if(!$line)continue;
  	if(++$row>$page['row_count'])continue;
    $cols=explode(",",$line);
    $col=0;
    foreach($cols as $colum){
      $col_specs=explode(":",$colum);
      if(count($col_specs)<2)$col_specs[1]=0;
      $page['rows'][($row)][$col]=$col_specs;
    	$col++;
      }
    }

  $wlist=load_page_word_list($pname."_fully.txt");

  return $page;
  }

function render_page_edit($page,$edit_link=NULL,$cell_edit=NULL,$border=1,$padding=2){
  
  $colspan=0;
  $rowspan=array();
  $edit=html_postget("cell_edit");

  $html="<table border=$border cellpadding=$padding cellspacing=0>";
  //$rowspans=array_fill(0, $page['col_count'], 0);
  for($i=0;$i<$page['row_count'];$i++){

    $html.="<tr>";
    for($j=0;$j<$page['col_count'];$j++){
       
      $cell_name=$page['name']."_".$i."_".$j;
      $cell_dat=fecth_cell($cell_name);
      $rowspan=@$page['rows'][$i][$j][0];
      $colspan=@$page['rows'][$i][$j][1];

      $rcspan="";
      if($rowspan>1)$rcspan.=" rowspan=$rowspan";
      if($colspan>1)$rcspan.=" colspan=$colspan";

      $html.="<td$rcspan>";
      if($edit_link){

        $html.="<a href=$edit_link$cell_name><font size=1 color=blue>edit cell</font></a><br>";
        $html.="rowspan<a href=$edit_link$cell_name&row=p><font size=1 color=blue>++</font></a> <a href=$edit_link$cell_name&row=m><font size=1 color=blue>--</font></a> ($rowspan)<br>";
        $html.="colspan<a href=$edit_link$cell_name&col=p><font size=1 color=blue>++</font></a> <a href=$edit_link$cell_name&col=m><font size=1 color=blue>--</font></a> ($colspan)<br>";
        $html.="$cell_name<hr>";
        }
      if(substr(@$cell_dat['raw'],0,5)=="page:"){
        if($embed_page=load_page_struct(substr(@$cell_dat['raw'],5))){
          if($rendered=render_page_edit($embed_page)){
            $html.=$rendered;
            }
          }
        }



      $html.=multi_line_render(@$cell_dat['draw']);
      if($cell_edit==$cell_name){
        $html.="<form action=$edit_link$cell_name&up=1 method=post>";
        $html.="Cell content<hr><textarea name=cell_content rows=10 cols=40>".@$cell_dat['raw']."</textarea><hr><hr>";
      	$html.="Cell description<hr><textarea name=celldesc_up rows=5 cols=40>".load_cell_desc($cell_name)."</textarea>";
        $html.="<input type=submit value=\"Update cell\">";        
        $html.="</form>";
        }

      $html.="</td>";

      if($colspan>1)$j+=$colspan-1;
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


function load_cell_desc($cell){
	global $pages_cells_dir;
	if(!$cell)return NULL;
  if(!$fdat=@file($pages_cells_dir.$cell."_desc.txt"))return "";
  $anna=implode($fdat);
  return $anna;
  }


function save_cell_desc($cell,$desc){
	echo "!!(($cell,$desc))!";
	global $pages_cells_dir;
	//if(!safe_fname(@$cell))return NULL;
  if(!$desc)return NULL;
	echo "!!((aaa))!";
  $fname=$pages_cells_dir.$cell."_desc.txt";
  file_dump($fname,$desc);
  return;
  }

  function page_defs($pname){

   if($pname)$page=load_page_struct($pname);
   if(!@$page['desc'])return NULL;

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
            echo "<td bgcolor=white>".render_line(str_replace("..",",",$elar[0]))."  ".str_replace(" .. "," , ",@$elar[1])."</td>";
            }
          echo "</tr></table><br>";
          }
        }
      }
  }

?>

