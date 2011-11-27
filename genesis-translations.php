<?php
/**
* This plugin translates your Genesis powered WordPress site easily with one of the available languages. 
*
* @package GenesisTranslations
* @author Remkus de Vries
*
* Plugin Name: Genesis Translations
* Plugin URI: http://remkusdevries.com/
* Description: This plugin will translate Genesis in the available languages.
* Author: Remkus de Vries
* Version: 1.0
* Author URI: http://remkusdevries.com/
* License: GPLv2
* Text Domain: genesis-translations
* Domain Path: /languages/
*/

/**
* Defining Genesis Translation constants
* 
*/
define( 'GENTRANS_FILE','genesis-translations/genesis-translations.php' );
define( 'GENTRANS_VERSION','1.0' );

/**
 * The text domain for the plugin
 *
 * @since 1.0
 */
define( 'GTRANS_DOMAIN' , 'genesis-layout-extras' );

/**
 * Load the text domain for translation of the plugin
 * 
 * @since 1.0
 */
load_plugin_textdomain( 'genesis-translations', false, 'genesis-translations/languages' );

register_activation_hook( __FILE__, 'fst_genesis_translations_activation_check' );
/**
 * Checks for activated Genesis Framework and its minimum version before allowing plugin to activate
 *
 * @author Nathan Rice, Remkus de Vries
 * @uses fst_genesis_translations_activation_check()
 * @since 1.0
 * @version 1.0
 */
function fst_genesis_translations_activation_check() {

	$latest = '1.7';

	$theme_info = get_theme_data( get_template_directory() . '/style.css' );

	if ( basename( get_template_directory() ) != 'genesis' ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );  // Deactivate ourself
		wp_die( sprintf( __( 'Whoa.. the translation this plugin only works, really, when you have installed the %1$sGenesis Framework%2$s', GTRANS_DOMAIN ), '<a href="http://www.forsite.nu/go/genesis/" target="_new">', '</a>' ) );
	}

	$version = fst_genesis_translations_version_check( $theme_info['Version'], 3 );

	if ( version_compare( $version, $latest, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );  // Deactivate ourself
		wp_die( sprintf( __( 'Uhm, the thing of it is, you kinda need the %1$sGenesis Framework %2$s%3$s or greater for these translations to make any sense.', GTRANS_DOMAIN ), '<a href="http://www.forsite.nu/go/genesis/" target="_new">', $latest, '</a>' ) );
	}
}

/**
 * Defining the Genesis Language constants
 * 
 * @access public
 * @return void
 */
function fst_set_genesis_language_dir() {
	
	$fstlang = WP_CONTENT_DIR.'/plugins/' .str_replace( basename( __FILE__ ),"", plugin_basename( __FILE__ ) );
	
	define( 'GENESIS_LANGUAGES_DIR', $fstlang . 'genesis-translations/' );
	
}