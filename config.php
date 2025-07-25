<?php
$includes_dir="C:\\wamp64\\www\\uscript\\includes\\";
$chars_dir="C:\\wamp64\\www\\uscript\\chars\\";
$braks_dir="C:\\wamp64\\www\\uscript\\braks\\";
$img_dir="C:\\wamp64\\www\\uscript\\img\\";
$defs_dir="C:\\wamp64\\www\\uscript\\defs\\";
$render_dir="C:\\wamp64\\www\\uscript\\img\\render\\";
$presave_dir="C:\\wamp64\\www\\uscript\\img\\presave\\";
$pages_dir="C:\\wamp64\\www\\uscript\\pages\\";
$pages_cells_dir="C:\\wamp64\\www\\uscript\\pages\\cells\\";
$pages_words_dir="C:\\wamp64\\www\\uscript\\pages\\words\\";
$dslash="\\";

$http_base="http://127.0.0.1/uscript/";


//load optional settings flags
require_once($includes_dir."options.php");

//level 1
require_once($includes_dir."notice.php");
require_once($includes_dir."html.php");
require_once($includes_dir."file_ops.php");
require_once($includes_dir."debug.php");
require_once($includes_dir."binhex.php");
require_once($includes_dir."hexbin.php");
require_once($includes_dir."uscript_numbers_draw.php");
require_once($includes_dir."chunk_parse.php");
require_once($includes_dir."markup_chars.php");
require_once($includes_dir."markup_shortcuts.php");
require_once($includes_dir."markup_overrides.php");
require_once($includes_dir."markup_brakets.php");
require_once($includes_dir."svg_drawing.php");
require_once($includes_dir."svg_lines.php");
require_once($includes_dir."svg_shapes.php");
require_once($includes_dir."defmap.php");
require_once($includes_dir."defs.php");

//level 2
require_once($includes_dir."math.php");
require_once($includes_dir."strings.php");
require_once($includes_dir."chunk_funk.php");

//level 3
require_once($includes_dir."markup_img.php");
require_once($includes_dir."uscript_numbers.php");

//level 4
require_once($includes_dir."number_generator.php");

//level 5
require_once($includes_dir."markup_nums.php");

//level 6
require_once($includes_dir."markup_vars.php");

//level 7
require_once($includes_dir."markup.php");

//level 8
require_once($includes_dir."render_text.php");

//level 9
require_once($includes_dir."render_def.php");
require_once($includes_dir."pre_render.php");
require_once($includes_dir."markup_tools.php");

//level 10
require_once($includes_dir."presave.php");
require_once($includes_dir."page.php");



function load_all($load_cat){
  load_chars($load_cat);
  load_shortcuts($load_cat);
  load_brakets($load_cat);
  activate_def_folder($load_cat);
  }


//load basic libs by default
load_all("basic");
load_chars("math");
load_chars("phys");
load_chars("geom");
load_chars("proc");
load_chars("astro");
load_chars("vars");
load_chars("temporal");
load_chars("life");
load_chars("quantum");
activate_def_folder("phys");

load_overrides("overrides");


//html header
function uscript_head(){

  $hstr="";

  $hstr.="<script type=\"text/javascript\" src=\"js/cvi_tip_lib.js\"></script>\n";
  $hstr.="<script type=\"text/javascript\" src=\"js/maputil.js\"></script>\n";
  $hstr.="<script type=\"text/javascript\" src=\"js/mapper.js\"></script>\n";

  return $hstr;
  }

?>
