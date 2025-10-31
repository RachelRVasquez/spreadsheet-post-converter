<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://rachievee.com
 * @since      1.0.0
 *
 * @package    Spreadsheet_Post_Converter
 * @subpackage Spreadsheet_Post_Converter/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 */

namespace Rachievee\SpreadsheetPostConverter\Core;
class I18n
{
    private $plugin_name;

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    2.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            $this->plugin_name,
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }


}
