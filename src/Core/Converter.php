<?php

/**
 * The file that defines the core plugin class
 *
 * This is used to define internationalization, and Admin-specific hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 */

namespace Rachievee\SpreadsheetPostConverter\Core;

use Rachievee\SpreadsheetPostConverter\Admin\Admin;
use Rachievee\SpreadsheetPostConverter\Core\Loader;
use Rachievee\SpreadsheetPostConverter\Core\I18n;


class Converter
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Admin area and
     * the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function __construct()
    {
        $this->version = defined('SPREADSHEET_POST_CONVERTER_VERSION')
            ? SPREADSHEET_POST_CONVERTER_VERSION
            : '2.0.0';

        $this->plugin_name = 'spreadsheet-post-converter';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
    }

    /**
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    2.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        $this->loader = new Loader();
        $this->loader->run();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Spreadsheet_Post_Converter_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    2.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new I18n($this->plugin_name, $this->version);

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_spreadsheet_post_converter');
    }

    /**
     * Register all of the hooks related to the Admin area functionality
     * of the plugin.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Admin($this->plugin_name, $this->version);
        $plugin_admin->register();
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    2.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }
}
