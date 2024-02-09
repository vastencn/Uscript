<?php
$includes_dir="C:\\wamp64\\www\\uscript\\includes\\";
$chars_dir="C:\\wamp64\\www\\uscript\\chars\\";
$braks_dir="C:\\wamp64\\www\\uscript\\braks\\";
$dslash="\\";

//load optional settings flags
require_once($includes_dir."options.php");

//level 1
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

//level 2
require_once($includes_dir."math.php");
require_once($includes_dir."strings.php");
require_once($includes_dir."chunk_funk.php");

//level 3

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



function load_all($load_cat){
  load_chars($load_cat);
  load_shortcuts($load_cat);
  load_brakets($load_cat);
  }


//load basic libs by default
load_all("basic");

?>
