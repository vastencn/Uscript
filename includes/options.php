<?php

//whether to turn on char defintions
//this means make an image map with mouseover highlighting for each chararcter and a linking function
$defmap_on=TRUE;

//whether to load the brak text defs into the brak struct
//the struct is copied into the parsing array for each instance of usage
//so I think this should be turned off by default and the text def should be loaded into a separate lookup index
$braks_load_text_defs=FALSE;

//default px gap between rendered words/symbols (does not include the gap between strings of hex symbols when drawing a number)
$default_word_spacing=3;

//Show build a vocab list
//Recomended to turn it off by default if the system wuill be used alot and override before including the config file
$build_vocab_list=TRUE; //comment out this line to have it rtuned off by default
if(!@$build_vocab_list)$build_vocab_list=FALSE;

?>