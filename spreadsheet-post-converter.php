<?php
/**
 * @link              https://rachievee.com
 * @since             2.0.0
 * @package           Spreadsheet_Post_Converter
 *
 * @wordpress-plugin
 * Plugin Name:       Account Code to Post Type Converter
 * Plugin URI:        https://github.com/RachelRVasquez/spreadsheet-post-converter
 * Description:       Upload an Excel spreadsheet to convert account codes into post types, custom taxonomies, and post meta. Meant to showcase Rachel's code, not for public use.
 * Version:           2.0.0
 * Author:            Rachel R. Vasquez
 * Author URI:        https://rrvasquez.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spreadsheet-post-converter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
defined('ABSPATH') || exit;

const SPREADSHEET_POST_CONVERTER_VERSION = '2.0.0';

require_once __DIR__ . '/vendor/autoload.php';

use Rachievee\SpreadsheetPostConverter\Core\Activator;
use Rachievee\SpreadsheetPostConverter\Core\Deactivator;
use Rachievee\SpreadsheetPostConverter\Core\Converter;

// Activation and deactivation hooks
register_activation_hook(__FILE__, [Activator::class, 'activate']);
register_deactivation_hook(__FILE__, [Deactivator::class, 'deactivate']);

function rachievee_spc_run_plugin()
{
    $plugin = new Converter();
    $plugin->run();
}

add_action('plugins_loaded', 'rachievee_spc_run_plugin');

