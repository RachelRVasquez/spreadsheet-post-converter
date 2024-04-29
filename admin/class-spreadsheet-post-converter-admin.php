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


require_once(plugin_dir_path(__DIR__) . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\IReader;

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
	 * Register custom route for spreadsheet data to pass through on the admin
	 *
	 * @since    1.0.0
	 */
	public function register_sc_routes()
	{
		$version   = '1';
		$namespace = 'spreadsheet-converter/v' . $version;
		$base      = 'upload-spreadsheet-data';
		register_rest_route($namespace, '/' . $base . '/', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array($this, 'handle_sc_spreadsheet_data'),
				'args'                => array(),
				'permission_callback' => function () {
					return true;
				}
			)
		));
	}

	/**
	 * Handle data from uploaded spreadsheet and return a response
	 *
	 * @param [type] $request
	 * @since    1.0.0
	 */
	public function handle_sc_spreadsheet_data($request)
	{
		$post_data = $request->get_params();
		$response  = $post_data;

		if ($_FILES["spc_spreadsheet_upload"]["name"] != '') {
			$allowed_extension = array('xls', 'xlsx');
			$file_array        = explode(".", $_FILES['spc_spreadsheet_upload']['name']);
			$file_extension    = end($file_array);

			if (in_array($file_extension, $allowed_extension)) {
				$reader = IOFactory::createReader('Xlsx');
				$reader->setReadDataOnly(true);
				$spreadsheet = $reader->load($_FILES['spc_spreadsheet_upload']['tmp_name']);
				$worksheet   = $spreadsheet->getActiveSheet();
				// Get the highest row and column numbers referenced in the worksheet
				$highestRow             = $worksheet->getHighestDataRow(); // e.g. 10
				$highestColumn          = $worksheet->getHighestDataColumn(); // e.g 'D'
				$highestColumnIndex     = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
				$account_code_col_check = $worksheet->getCell('A1')->getValue();

				if ($highestColumn !== 'D') {
					$response = '<div class="notice notice-error">The spreadsheet\'s column count does not match with what is expected. ' . $highestColumn . '</div>';
				} else if ($account_code_col_check !== 'Account Code') {
					$response = '<div class="notice notice-error">Account code column missing from spreadsheet.</div>';
				} else {
					//loop through spreadsheet and clean up
					$clean_spreadsheet_array = [];
					$account_code = '';
					for ($row = 1; $row <= $highestRow; ++$row) {
						for ($col = 1; $col <= $highestColumnIndex; ++$col) {
							$value        = $worksheet->getCell([$col, $row])->getValue();

							var_dump($col);
							var_dump($row);
							var_dump($value);

							//empty values as empty strings, cleaner
							if (is_null($value)) {
								$value = '';
							}

							//turn years to integers, floats for some reason
							if (is_float($value)) {
								$value = intval($value);
							}

							//Set account code for indexes
							if ($col === 1 && $row !== 1) {
								$account_code = $value;
							}

							if ($col !== 1 && $row !== 1 && !empty($account_code) && !is_null($account_code)) {
								//skip column 1 since we're already using it (account codes) as the array indexes	
								//skip row 1 since that's just the labels for the cols?
								//Col 1 Account Code, 2 Budget, 3 Department, 4 Year
								//skips any row without an account code
								//@todo: bonus, add column names as indexes
								$clean_spreadsheet_array[$account_code][] = $value;
							}
						}
					}

					var_dump($clean_spreadsheet_array);

					//comment out this line or set $account_code_posts_created to true/false if you're testing
					//@todo: Create function that handles post types, taxonomies, post meta 
					//$account_code_posts_created = $this->create_account_code_posts( $clean_spreadsheet_array );
					$account_code_posts_created = true;


					if (!$account_code_posts_created) {
						$response = '<div class="notice notice-error">Something went wrong when creating the account code post</div>';
					} else {
						//@todo: WRITER NOT WORKING?
						$writer   = IOFactory::createWriter($spreadsheet, 'Html');
						$response = '<div class="notice notice-success">The following account codes have been imported.</div>';
					}
				}
			} else {
				$response = '<div class="notice notice-error">Only .xls or .xlsx files are allowed.</div>';
			}
		} else {
			$response = '<div class="notice notice-error">Please select a file before uploading.</div>';
		}

		return new WP_REST_Response($response, 200);
	}

	public function create_account_code_posts($spreadsheet_data)
	{
		return true;
	}

	/**
	 * Create the admin area menu/form page
	 *
	 * @since    1.0.0
	 */
	public function create_sc_admin_page()
	{
		add_menu_page(
			__('Convert Spreadsheet', 'spreadsheet-post-converter'),
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
			array($this, 'get_spreadsheet_post_converter_template'),
		);
	}

	/**
	 * Fetch front-end template for admin menu page
	 *
	 * @since    1.0.0
	 */
	public function get_spreadsheet_post_converter_template()
	{
		include(plugin_dir_path(__DIR__) . 'admin/partials/spreadsheet-post-converter-admin-display.php');
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

	/**
	 * Custom Post Type for spreadsheet data
	 *
	 * @since    1.0.0
	 */
	function create_account_code_cpt()
	{

		$labels = array(
			'name'                  => _x('Account Codes', 'Post Type General Name', 'spreadsheet-post-converter'),
			'singular_name'         => _x('Account Code', 'Post Type Singular Name', 'spreadsheet-post-converter'),
			'menu_name'             => __('Account Codes', 'spreadsheet-post-converter'),
			'name_admin_bar'        => __('Account Code', 'spreadsheet-post-converter'),
			'attributes'            => __('Account Code Attributes', 'spreadsheet-post-converter'),
			'parent_item_colon'     => __('Parent Item:', 'spreadsheet-post-converter'),
			'all_items'             => __('All Account Codes', 'spreadsheet-post-converter'),
			'add_new_item'          => __('Add New Account Code', 'spreadsheet-post-converter'),
			'add_new'               => __('Add New', 'spreadsheet-post-converter'),
			'new_item'              => __('New Account Code', 'spreadsheet-post-converter'),
			'edit_item'             => __('Edit Account Code', 'spreadsheet-post-converter'),
			'update_item'           => __('Update Account Code', 'spreadsheet-post-converter'),
			'view_item'             => __('View Account Code', 'spreadsheet-post-converter'),
			'view_items'            => __('View Account Codes', 'spreadsheet-post-converter'),
			'search_items'          => __('Search Account Code', 'spreadsheet-post-converter'),
			'not_found'             => __('Not found', 'spreadsheet-post-converter'),
			'not_found_in_trash'    => __('Not found in Trash', 'spreadsheet-post-converter'),
			'uploaded_to_this_item' => __('Uploaded to this item', 'spreadsheet-post-converter'),
			'items_list'            => __('Account code list', 'spreadsheet-post-converter'),
			'items_list_navigation' => __('Account code list navigation', 'spreadsheet-post-converter'),
			'filter_items_list'     => __('Filter account code list', 'spreadsheet-post-converter'),
		);
		$args = array(
			'label'                 => __('Account Code', 'spreadsheet-post-converter'),
			'description'           => __('Account Codes', 'spreadsheet-post-converter'),
			'labels'                => $labels,
			'supports'              => array('title', 'custom-fields'),
			'taxonomies'            => array('department', ' budget_year'),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-media-text',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => false,
		);

		register_post_type('account_code', $args);
	}

	/**
	 * Custom taxonomy for departments (used for account code cpt)
	 *
	 * @since    1.0.0
	 */
	function create_department_taxonomy()
	{

		$labels = array(
			'name'                       => _x('Departments', 'Taxonomy General Name', 'spreadsheet-post-converter'),
			'singular_name'              => _x('Department', 'Taxonomy Singular Name', 'spreadsheet-post-converter'),
			'menu_name'                  => __('Department', 'spreadsheet-post-converter'),
			'all_items'                  => __('All Departments', 'spreadsheet-post-converter'),
			'parent_item'                => __('Parent Item', 'spreadsheet-post-converter'),
			'parent_item_colon'          => __('Parent Item:', 'spreadsheet-post-converter'),
			'new_item_name'              => __('New Department', 'spreadsheet-post-converter'),
			'add_new_item'               => __('Add New Department', 'spreadsheet-post-converter'),
			'edit_item'                  => __('Edit Department', 'spreadsheet-post-converter'),
			'update_item'                => __('Update Department', 'spreadsheet-post-converter'),
			'view_item'                  => __('View Department', 'spreadsheet-post-converter'),
			'separate_items_with_commas' => __('Separate departments with commas', 'spreadsheet-post-converter'),
			'add_or_remove_items'        => __('Add or remove departments', 'spreadsheet-post-converter'),
			'choose_from_most_used'      => __('Choose from the most used', 'spreadsheet-post-converter'),
			'popular_items'              => __('Popular Departments', 'spreadsheet-post-converter'),
			'search_items'               => __('Search Departments', 'spreadsheet-post-converter'),
			'not_found'                  => __('Not Found', 'spreadsheet-post-converter'),
			'no_terms'                   => __('No Departments', 'spreadsheet-post-converter'),
			'items_list'                 => __('Department list', 'spreadsheet-post-converter'),
			'items_list_navigation'      => __('Department list navigation', 'spreadsheet-post-converter'),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		);
		register_taxonomy('department', array('account_code'), $args);
	}

	/**
	 * Custom taxonomy for budget year (used for account code cpt)
	 *
	 * @since    1.0.0
	 */
	function create_budget_year_taxonomy()
	{

		$labels = array(
			'name'                       => _x('Budget Years', 'Taxonomy General Name', 'spreadsheet-post-converter'),
			'singular_name'              => _x('Budget Year', 'Taxonomy Singular Name', 'spreadsheet-post-converter'),
			'menu_name'                  => __('Budget Year', 'spreadsheet-post-converter'),
			'all_items'                  => __('All Years', 'spreadsheet-post-converter'),
			'parent_item'                => __('Parent Item', 'spreadsheet-post-converter'),
			'parent_item_colon'          => __('Parent Item:', 'spreadsheet-post-converter'),
			'new_item_name'              => __('New Budget Year', 'spreadsheet-post-converter'),
			'add_new_item'               => __('Add New Budget Year', 'spreadsheet-post-converter'),
			'edit_item'                  => __('Edit Budget Year', 'spreadsheet-post-converter'),
			'update_item'                => __('Update Budget Year', 'spreadsheet-post-converter'),
			'view_item'                  => __('View Budget Year', 'spreadsheet-post-converter'),
			'separate_items_with_commas' => __('Separate budget years with commas', 'spreadsheet-post-converter'),
			'add_or_remove_items'        => __('Add or remove budget years', 'spreadsheet-post-converter'),
			'choose_from_most_used'      => __('Choose from the most used', 'spreadsheet-post-converter'),
			'popular_items'              => __('Popular Budget Years', 'spreadsheet-post-converter'),
			'search_items'               => __('Search Budget Years', 'spreadsheet-post-converter'),
			'not_found'                  => __('Not Found', 'spreadsheet-post-converter'),
			'no_terms'                   => __('No Budget Years', 'spreadsheet-post-converter'),
			'items_list'                 => __('Budget year list', 'spreadsheet-post-converter'),
			'items_list_navigation'      => __('Year list navigation', 'spreadsheet-post-converter'),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		);
		register_taxonomy('budget_year', array('account_code'), $args);
	}
}
