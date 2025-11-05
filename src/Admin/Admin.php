<?php
/**
 * Admin functionality
 *
 * Creates menu pages, functions for the dashboard display, and handling spreadsheet data
 *
 * @todo: Create new file for handling spreadsheet data
 */

namespace Rachievee\SpreadsheetPostConverter\Admin;

use Rachievee\SpreadsheetPostConverter\Admin\Routes\SpreadsheetRoute;
use Rachievee\SpreadsheetPostConverter\Admin\Services\Assets;
use Rachievee\SpreadsheetPostConverter\Admin\Services\PostCreator;
use Rachievee\SpreadsheetPostConverter\Admin\Services\SpreadsheetHandler;
use Rachievee\SpreadsheetPostConverter\Admin\Services\Structure;

class Admin
{
    private $plugin_name;

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->structure = new Structure();
        $this->assets = new Assets();
        $post_creator = new PostCreator();
        $handler = new SpreadsheetHandler($post_creator);
        $this->route = new SpreadsheetRoute($handler);
    }

    public function register()
    {
        add_action('init', [$this->structure, 'register']);
        add_action('admin_enqueue_scripts', [$this->assets, 'enqueue_scripts_and_styles']);
        add_action('admin_menu', [$this, 'create_sc_admin_page']);
        add_action('rest_api_init', [$this->route, 'register']);
    }

    /**
     * Create the Admin area menu/form page
     *
     * Called from the Converter
     *
     * @since    2.0.0
     */
    public function create_sc_admin_page()
    {
        add_menu_page(
            __('Convert Spreadsheet', $this->plugin_name),
            'Convert Spreadsheet',
            'manage_options',
            $this->plugin_name,
            false,
            'dashicons-media-spreadsheet'
        );

        add_submenu_page(
            $this->plugin_name,
            'Convert Spreadsheet',
            'Dashboard',
            'manage_options',
            $this->plugin_name,
            array($this, 'render_page'),
        );
    }

    /**
     * Fetch front-end template for Admin menu page
     *
     * @since    2.0.0
     */
    public function render_page(): void
    {
        include __DIR__ . '/Templates/admin-display.php';
    }


}
