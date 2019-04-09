=== 8080 Barcode Generator ===
Contributors: dwawobieg
Donate link: http://www.mlipinski.pl/donate
Requires at least: 3.3
Stable tag: 1.0.3
Contributors: dwawobieg
Tags: code128, barcode, barcode generator, code39, code25
Tested up to: 4.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use a simple shortcode to generate barcode.

== Description ==
With 8080 Barode Genertor you can generte barcodes in CODE128, CODE39, CODE25, CODABAR.
To add barcode in yours  site you have to only put simple shortcode wherever you want.
[**DEMO**](http://www.mlipinski.pl/plugins/barcode-generator/)

**For example:**
`
[barcode text="1234test" size="100" text_size="10" codetype="code128"]
`
This  shortcode  generate barcode with code type CODE128 coding "1234test" height of barcode is 100px and size of text is 10px.

For advanced usage you can use  `m8080_barcode` filter:
`
add_filter('m8080_barcode','m8080_test');
function m8080_test($attr){
	$attr['text']='1243abcert2';
	$attr['size']=150;
	$attr['text_size']=20;
	$attr['code_type']='code128';
	return $attr;
}
`

== Installation ==
Upload 8080-barcode-generator directory to the /wp-content/plugins/ directory
Activate the plugin through the \'Plugins\' menu in WordPress
Use shortcode wherever you want.

== Screenshots ==
1. examle of  CODE128

== Changelog ==
1.0.3 Add capturing the output buffer for display shortcode.

1.0.2 Little changes in readme.txt

1.0.1 Little changes in readme.txt

1.0 First version