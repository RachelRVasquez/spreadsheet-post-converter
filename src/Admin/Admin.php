<?php
/**
 * Admin functionality
 *
 * Creates menu pages, functions for the dashboard display, and handling spreadsheet data
 *
 * @todo: Create new file for handling spreadsheet data
 */

namespace Rachievee\SpreadsheetPostConverter\Admin;

use Rachievee\SpreadsheetPostConverter\Admin\Services\Structure;
use Rachievee\SpreadsheetPostConverter\Admin\Services\SpreadsheetHandler;
use Rachievee\SpreadsheetPostConverter\Admin\Routes\SpreadsheetRoute;

class Admin
{
    public function __construct()
    {

        $this->structure = new Structure();
        $this->assets = new Assets();
        $this->handler = new SpreadsheetHandler();
        $this->route = new SpreadsheetRoute($this->handler);
    }

    public function register() {
        add_action('init', [$this->structure, 'register']);
        add_action('admin_enqueue_scripts', [$this->assets, 'enqueue']);
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

    /**
     * Register the Styles and JavaScript for the Admin area.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts()
    {
        $plugin_url = plugin_dir_url(__FILE__);
        wp_enqueue_style(
            'spc-admin',
            $plugin_url . 'css/admin.css',
            [],
            '1.0.0'
        );
        wp_enqueue_script(
            'spc-admin',
            $plugin_url . 'js/admin.js',
            ['wp-api', 'jquery'],
            '1.0.0',
            true
        );
    }
}
