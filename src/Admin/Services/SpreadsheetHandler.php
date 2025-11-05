<?php

namespace Rachievee\SpreadsheetPostConverter\Admin\Services;

use WP_REST_Server;
use Rachievee\SpreadsheetPostConverter\Admin\Services\PostCreator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\IReader;

class SpreadsheetHandler
{
    private $post_creator;

    public function __construct(PostCreator $post_creator)
    {
        $this->post_creator = $post_creator;
    }
    /**
     * Handle data from uploaded spreadsheet and return a response
     *
     * @param [type] $request
     * @since    2.0.0
     */
    public function handle_sc_spreadsheet_data(object $request): object
    {
        $response = $request->get_params();

        if ($_FILES["spc_spreadsheet_upload"]["name"] != '') {
            $allowed_extension = array('xls', 'xlsx');
            $file_array = explode(".", $_FILES['spc_spreadsheet_upload']['name']);
            $file_extension = end($file_array);

            if (in_array($file_extension, $allowed_extension)) {
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($_FILES['spc_spreadsheet_upload']['tmp_name']);
                $worksheet = $spreadsheet->getActiveSheet();
                // Get the highest row and column numbers referenced in the worksheet
                $highestRow = $worksheet->getHighestDataRow(); // e.g. 10
                $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'D'
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                $account_code_col_check = $worksheet->getCell('A1')->getValue();

                if ($highestColumn !== 'D') {
                    $response = [
                        'message' => '<div class="notice notice-error">The spreadsheet\'s column count does not match with what is expected. ' . $highestColumn . '</div>',
                        'output' => false,
                    ];
                } else if ($account_code_col_check !== 'Account Code') {
                    $response = [
                        'message' => '<div class="notice notice-error">Account code column missing from spreadsheet.</div>',
                        'output' => false,
                    ];
                } else {
                    //loop through spreadsheet and clean up
                    $clean_spreadsheet_array = [];
                    $account_code = '';
                    for ($row = 1; $row <= $highestRow; ++$row) {
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            $value = $worksheet->getCell([$col, $row])->getValue();

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

                    //comment out this line or set $account_code_posts_created to true/false if you're testing
                    $account_code_posts_created = $this->post_creator->create_account_code_posts($clean_spreadsheet_array);

                    if (!$account_code_posts_created) {
                        $response = [
                            'message' => '<div class="notice notice-error">Something went wrong when creating the account code post</div>',
                            'output' => false,
                        ];
                    } else {
                        $writer = IOFactory::createWriter($spreadsheet, 'Html');
                        // $writer->generateStyles( false );
                        //@todo: HTML headers interfering with WP, remove somehow, how?
                        // $writer_output = $writer->save( 'php://output' );


                        $response = [
                            'message' => '<div class="notice notice-success">Account codes have been imported and converted to posts, please review.</div>',
                            // 'output'  => json_encode( $writer_output ),
                            'output' => true,
                        ];

                        return new \WP_REST_Response($response, 200);
                    }
                }
            } else {
                $response = [
                    'message' => '<div class="notice notice-error">Only .xls or .xlsx files are allowed.</div>',
                    'output' => false
                ];
            }
        } else {
            $response = [
                'message' => '<div class="notice notice-error">Please select a file before uploading.</div>',
                'output' => false,
            ];
        }

        return new WP_REST_Response($response, 200);
    }
}
