<?php

namespace Rachievee\SpreadsheetPostConverter\Admin\Services;
class Assets
{
    /**
     * Register the Styles and JavaScript for the Admin area.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts_and_styles()
    {
        $plugin_url = \plugin_dir_url(__FILE__);
        \wp_enqueue_style(
            'spc-admin',
            $plugin_url . 'css/admin.css',
            [],
            '1.0.0'
        );
        \wp_enqueue_script(
            'spc-admin',
            $plugin_url . 'js/admin.js',
            ['wp-api', 'jquery'],
            '1.0.0',
            true
        );
    }
}
