<?php
/**
* 
* Plugin Name: Genesis Translations
* Plugin URI: http://remkusdevries.com/
* Description: This plugin will translate Genesis in the available languages.
* Author: Remkus de Vries
* Version: 1.0
* Author URI: http://remkusdevries.com/
*/

/**
* Defining Genesis Translation constants
* 
*/
define( 'GENTRANS_FILE','genesis-translations/genesis-translations.php' );
define( 'GENTRANS_VERSION','1.0' );


add_action( 'load_textdomain','fst_set_genesis_language_dir' );
/**
 * Defining the Genesis Language constants
 * 
 * @access public
 * @return void
 */
function fst_set_genesis_language_dir() {
	
	$fstlang = WP_CONTENT_DIR.'/plugins/' .str_replace( basename( __FILE__ ),"", plugin_basename( __FILE__ ) );
	
	define( 'GENESIS_LANGUAGES_DIR', $fstlang . 'languages/' );
	
}