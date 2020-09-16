<?php 
/**
 * Plugin Name:       Covidwebmd Assessment
 * Plugin URI:        http://themes.tradesouthwest.com/wordpress/plugins/
 * Description:       Add COVID-19 questionaire to your website. Uses the embeded synidicated solution from Webmd.com. This tool does not provide medical advice. There is nothing to setup, plugin creaters a page an embeds the shortcode, upon activation.
 * Version:           1.0.0
 * Author:            Larry Judd
 * Author URI:        http://tradesouthwest.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Requires Package: 
 * Requires at least: 4.5
 * Tested up to:      5.2.2
 * Requires PHP:      5.4
 * Text Domain:       covidwebmd-assessment
 * Domain Path:       /languages
 * https://www.webmd.com/coronavirus/coronavirus-assessment/default.htm
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Constants defined here. 
if (!defined('COVIDWEBMD_URL')) { define( 'COVIDWEBMD_URL', plugin_dir_url(__FILE__)); }

/**
 * Upon Activation plugin adds a shortcode to an assigned page.
 */
function covidwebmd_assessment_plugin_activate()
{
	$title = 'COVID-19 Symptoms Assessment';
	$slug = 'covidwebmd-assessment';
	$author_id = 1;

    if( null == get_page_by_title( $title ) ) {
        // Create the page
            wp_insert_post(array(
                'post_type' => 'page',
                'post_title' => $title,
                'post_author' => $author_id,
                'post_status' => 'publish',
                'post_content' => '[covidwebmd_assessment]',
                'post_name' => $slug,
        ));

    } 
}
function covidwebmd_assessment_plugin_deactivation() {
    //covidwebmd_assessment_deregister_shortcode() 
        return false;
}

//load language scripts     
function covidwebmd_assessment_load_text_domain() 
{
    load_plugin_textdomain( 'covidwebmd-assessment', false, 
    basename( dirname( __FILE__ ) ) . '/languages' ); 
}

// Register activation hook with WP core.
register_activation_hook( __FILE__, 'covidwebmd_assessment_plugin_activate' );
register_deactivation_hook( __FILE__, 'covidwebmd_assessment_plugin_deactivation' );

//enqueue or localise scripts
function covidwebmd_assessment_enqueue_public_style() 
{
    wp_enqueue_style( 'covidwebmd-assessment-style', COVIDWEBMD_URL 
                      . '/css/covidwebmd-assessment-style.css', array(), '', false );
}
add_action( 'wp_enqueue_scripts', 'covidwebmd_assessment_enqueue_public_style' );


//register shortcodes
function covidwebmd_assessment_register_shortcodes() {
    
    add_shortcode( 'covidwebmd_assessment', 'covidwebmd_assessment_embeded_shortcode_content' );
}
add_action( 'init', 'covidwebmd_assessment_register_shortcodes' );

// Output for shortcode
function covidwebmd_assessment_embeded_shortcode_content()
{
    ob_start();
    ?>

    <div id="covidwebmd-container">
        <div class="covidwebmd-inner">
            <iframe src="https://www.webmd.com/covid-assessment-syndication" frameborder="0" width="100%" height="600px"></iframe>
        </div>
            <footer>
                <small>© <?php echo date('Y'); esc_html_e( ' WebMD, LLC. All rights reserved.', 'covidwebmd-assessment' ); ?></small>
            </footer>
    </div>

    <?php 
    
        return ob_get_clean();
} 
?>