<?php
/**
 * Deactivator class to add optional functions
 *
 * @since 2.0.0
 */

namespace Rachievee\SpreadsheetPostConverter\Core;
class Deactivator
{

    /**
     * @return void
     */
    public static function deactivate() : void
    {
        //Optional: Potential things to do here
        //Clean up scheduled cron jobs (this doesn't have any, but for example)
        //Flush rewrite rules due to adding CPTs
        //Stop background processes

        flush_rewrite_rules();
    }

}
