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
 * @since      1.0.0
 * @package    Spreadsheet_Post_Converter
 * @subpackage Spreadsheet_Post_Converter/includes
 * @author     Rachel R. Vasquez <rachelrvasquez@gmail.com>
 */
class Spreadsheet_Post_Converter_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'spreadsheet-post-converter',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
