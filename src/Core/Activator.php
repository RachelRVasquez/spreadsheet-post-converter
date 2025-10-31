<?php
/**
 * Activator class to add optional functions
 *
 * @since 2.0.0
 */

namespace Rachievee\SpreadsheetPostConverter\Core;
class Activator
{
    /**
     * @return void
     */
    public static function activate()
    {
        //Optional: Potential things to do here
        //Create custom database tables
        //Set up default options
        //Confirm custom post types or roles exist
        //Run migration scripts

        flush_rewrite_rules();
    }
}
