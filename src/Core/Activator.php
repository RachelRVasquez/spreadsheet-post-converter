<?php
/**
 * Activator class to add optional functions
 *
 * @since 2.0.0
 */

namespace Rachievee\SpreadsheetPostConverter\Core;

use Rachievee\SpreadsheetPostConverter\Admin\Admin;
class Activator
{
    /**
     * @return void
     */
    public static function activate() : void
    {
        //Optional: Potential things to do here
        //Create custom database tables
        //Set up default options
        //Confirm custom post types or roles exist
        //Run migration scripts

        $admin = new Admin( 'spreadsheet-post-converter', '2.0.0' );
        $admin->create_account_code_cpt();
        $admin->create_department_taxonomy();
        $admin->create_budget_year_taxonomy();

        flush_rewrite_rules();
    }
}
