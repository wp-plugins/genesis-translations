<?php
/**
* @package GenesisTranslation
* @version 1.0
*
* Plugin Name: Gen Translation
* Plugin URI: http://remkusdevries.com/
* Description: This is me conquering the world.
* Author: Remkus de Vries
* Version: 0.9
* Author URI: http://remkusdevries.com/
*/

/**
* Defining Genesis Translation constants
* 
*/
define( 'GENTRANS_README_URL', 'http://plugins.trac.wordpress.org/browser/genesis-translations/trunk/readme.txt?format=txt' );
define( 'GENTRANS_FILE','genesis-translations/genesis-translations.phpp' );
define( 'GENTRANS_VERSION','0.9' );


add_action( 'load_textdomain','set_genesis_language_dir' );
/**
 * Defining the Genesis Language constants.
 */
/**
 * set_genesis_language_dir function.
 * 
 * @access public
 * @return void
 */
function set_genesis_language_dir() {
	$fstlang = WP_CONTENT_DIR.'/plugins/' .str_replace( basename( __FILE__ ),"", plugin_basename( __FILE__ ) );
	define( 'GENESIS_LANGUAGES_DIR', $fstlang . 'genesis-translations/' );
}

add_action('admin_notices', 'fst_hey_did_you_add_child_theme_language_files_message');
/**
 * Display notification so people know this plugin overrides the functions.php settings.
 * 
 * @author: Remkus de Vries
 * @since 1.0
 */
function fst_hey_did_you_add_child_theme_language_files_message() {

	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'genesis-translations' )
		return;

	if ( class_exists('Ecordia') || get_option('fst_disable_notification') )
		return;

	printf( '<div class="updated" style="overflow: hidden;"><p class="alignleft">If you have used the tutorial on dev.studiopress.com before on <a href="%s" target="_blank">How to Keep Translation Active When Updating Genesis</a> then you should remove the two lines you have added to your <code>functions.php</code> as this plugin will override those two lines anyway.</p> <p class="alignright"><a href="%s">Dismiss</a></p></div>', 'http://dev.studiopress.com/maintain-translation-upgrade.htm', add_query_arg( 'dismiss-language-notification', 'true', menu_page_url( 'genesis-translations', false ) ) );

}

add_action('admin_init', 'fst_disable_notification');
/**
 * This function detects a query flag, and disables the notification,
 * then redirects the user back to the Genesis Translations page.
 * @author: Remkus de Vries, StudioPress
 * @since 1.0
 */
function fst_disable_notification() {

	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'genesis-translations' )
		return;

	if ( !isset($_REQUEST['dismiss-language-notification']) || $_REQUEST['dismiss-language-notification'] !== 'true' )
		return;

	update_option( 'genesis-translation-notification-disabled', 1 );

	genesis_admin_redirect( 'genesis-translations' );
	exit;
}


add_action('admin_menu', 'fst_add_genesis_translations_submenus', 69);
/**
 * The fst_add_genesis_translations_submenus function adds our "Genesis Translations" submenu item
 * 
 * @access public
 * @return void
 */
function fst_add_genesis_translations_submenus() {
	
	add_submenu_page( 'genesis', __( 'Genesis Translations','GEN_TRANS' ), __( 'Genesis Translations','GEN_TRANS' ), 'manage_options', 'genesis-translations', 'fst_genesis_translations_page' );
	
}

/**
 * This function is what actually gets output to the page.
 * 
 * @author Remkus de Vries, StudioPress
 * @global string $_prose_settings_pagehook
 * @global integer $screen_layout_columns
 * @version 1.0
 */
 
 
 
function fst_genesis_translations_page() { 
?>	
	<div id="genesis-translations" class="wrap genesis-metaboxes">
		<?php screen_icon( 'themes' ); ?>
		
		<h2><?php echo 'Genesis '; _e( 'Genesis Translations', GEN_TRANS ); ?></h2>			
		
		<?php
    	   	$store = get_transient( 'genesis-translations-informatiaaaa' );
       
       		if( !$store ) {
           		$store = wp_remote_retrieve_body( wp_remote_request( 'http://remkusdevries.com/plugins/genesis-translations/log/' ) );
           		set_transient( 'genesis-translations-informatiaaaa', $store, 60*60*12 ); // store for 12 hours
       		}
       
       		echo $store;
   		?>
   
	</div>
<?php
}

add_action( 'in_plugin_update_message-' . GEN_TRANS_FILE, array( &$this, 'in_plugin_update_message' ) );
/**
 * Show plugin changes in the plugins menu based on the W3 Total Cache Plugin
 * by Frederick Townes
 * 
 * @return void
 * @author: Frederick Townes, Remkus de Vries
 * @since 1.0
 */
function fst_in_plugin_update_message() {
        $response = fst_http_get( GENTRANS_README_URL );

        if ( !is_wp_error($response) && $response['response']['code'] == 200 ) {
            $matches = null;
            $regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote(GENTRANS_VERSION) . '\s*=|$)~Uis';

            if ( preg_match( $regexp, $response['body'], $matches ) ) {
                $changelog = ( array ) preg_split( '~[\r\n]+~', trim($matches[1] ) );

                echo '<div style="color: #f00;">Take a minute to update, here\'s why:</div><div style="font-weight: normal;">';
                $ul = false;

                foreach ($changelog as $index => $line) {
                    if (preg_match('~^\s*\*\s*~', $line)) {
                        if (!$ul) {
                            echo '<ul style="list-style: disc; margin-left: 20px;">';
                            $ul = true;
                        }
                        $line = preg_replace('~^\s*\*\s*~', '', htmlspecialchars($line));
                        echo '<li style="width: 50%; margin: 0; float: left; ' . ($index % 2 == 0 ? 'clear: left;' : '') . '">' . $line . '</li>';
                    } else {
                        if ($ul) {
                            echo '</ul><div style="clear: left;"></div>';
                            $ul = false;
                        }
                        echo '<p style="margin: 5px 0;">' . htmlspecialchars($line) . '</p>';
                    }
                }

                if ($ul) {
                    echo '</ul><div style="clear: left;"></div>';
                }

                echo '</div>';
            }
        }
    }    
/**
 * Download url via GET
 *
 * @param string $url
 * @param string $auth
 * $param boolean $check_status
 * @return string
 */
function fst_http_get( $url, $auth = '', $check_status = true ) {
    return fst_http_request( 'GET', $url, null, $auth, $check_status );
}

register_activation_hook( __FILE__, 'fst_gentrans_activate' );
add_action( 'admin_init', 'fst_gentrans_redirect' );

function fst_gentrans_activate() {
    add_option( 'fst_gentrans_do_activation_redirect', true );
}

function fst_gentrans_redirect()
{
    if (get_option( 'fst_gentrans_do_activation_redirect', false ) ) {
        delete_option( 'fst_gentrans_do_activation_redirect' );
        wp_redirect( MY_PLUGIN_SETTINGS_URL );
    }
}