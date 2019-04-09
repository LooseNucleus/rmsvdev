<?php
/*
Plugin Name:  Barcode generator
Plugin URI: http://www.mlipinski.pl/plugins/barcode-generator
Description: Display barcode as a png.
Version: 1.0.3
Author: Michal Lipinski
Author URI: http://www.mlipinski.pl
License: GPLv2
*/

add_shortcode ('barcode','m8080_generate_barcode');

function m8080_generate_barcode($attr){
	$attr=apply_filters('m8080_barcode',$attr);
	$text = (isset($attr["text"])?$attr["text"]:"0");
	$size = (isset($attr["size"])?$attr["size"]:"20");
	$text_size = (isset($attr["text_size"])?$attr["text_size"]:"0");
	$code_type = (isset($attr["code_type"])?$attr["code_type"]:"code128");
	
	ob_start();
	echo '<img src="'.plugin_dir_url(__FILE__).'lib/barcode.php?text='.$text.'&size='.$size.'&size_tekst='.$text_size.'&codetype='.$code_type.'" alt="barcode"/>';
	$view = ob_get_clean();
    return $view;
}

?>