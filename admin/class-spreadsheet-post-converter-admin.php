<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rachievee.com
 * @since      1.0.0
 *
 * @package    Spreadsheet_Post_Converter
 * @subpackage Spreadsheet_Post_Converter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Spreadsheet_Post_Converter
 * @subpackage Spreadsheet_Post_Converter/admin
 * @author     Rachel R. Vasquez <rachelrvasquez@gmail.com>
 */
class Spreadsheet_Post_Converter_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function register_sc_routes(){
		register_rest_route(
			'spreadsheet-converter',
			'/v1/upload-spreadsheet-data',
			array(
				'methods' => 'POST',
				'callback' => 'handle_sc_spreadsheet_data',
				'args' => array(),
			)
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $request
	 * @return void
	 */
	public function handle_sc_spreadsheet_data( $request ){
		$post_data = $request->get_params();
     	$response  = $post_data;

		//@todo: write functionality here

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function create_sc_admin_page(){
		add_menu_page(
			__( 'Convert Spreadsheet', 'spreadsheet-post-converter' ),
			'Convert Spreadsheet',
			'manage_options',
			'spreadsheet-post-converter',
			false,
			'dashicons-media-spreadsheet',
			''
		);

		add_submenu_page(
			'spreadsheet-post-converter',
			'Convert Spreadsheet',
			'Dashboard',
			'manage_options',
			'spreadsheet-post-converter',
			array( $this, 'get_spreadsheet_post_converter_template' ),
		);
	}

	public function get_spreadsheet_post_converter_template() {
		include( plugin_dir_path( __DIR__ ) . 'admin/partials/spreadsheet-post-converter-admin-display.php' );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spreadsheet_Post_Converter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spreadsheet_Post_Converter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/spreadsheet-post-converter-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spreadsheet_Post_Converter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spreadsheet_Post_Converter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/spreadsheet-post-converter-admin.js', array('jquery'), $this->version, false);
	}
}
