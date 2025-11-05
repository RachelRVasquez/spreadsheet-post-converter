<?php

namespace Rachievee\SpreadsheetPostConverter\Admin\Routes;

use WP_REST_Server;
use Rachievee\SpreadsheetPostConverter\Admin\Services\SpreadsheetHandler;
class SpreadsheetRoute
{
    private $handler;

    public function __construct(SpreadsheetHandler $handler)
    {
        $this->handler = $handler;
    }

    public function register()
    {
        $this->register_sc_routes();
    }

    private function register_sc_routes()
    {
        $version = '1';
        $namespace = 'spreadsheet-converter/v' . $version;
        $base = 'upload-spreadsheet-data';

        register_rest_route($namespace, '/' . $base . '/', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this->handler, 'handle_sc_spreadsheet_data'],
                'permission_callback' => function () {
                    return true;
                }
            )
        ));
    }

}
