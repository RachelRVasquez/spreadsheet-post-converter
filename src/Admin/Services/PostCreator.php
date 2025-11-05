<?php

namespace Rachievee\SpreadsheetPostConverter\Admin\Services;

class PostCreator
{
    public function __construct()
    {
    }

    /**
     * Create account code posts, add custom taxonomies and post meta from spreadsheet data
     *
     * @param [array] $spreadsheet_data
     * @since    2.0.0
     */
    public function create_account_code_posts(array $spreadsheet_data): bool
    {
        foreach ($spreadsheet_data as $account_code => $account_code_data) {
            $get_budget = $account_code_data[0] ? sanitize_text_field($account_code_data[0]) : '';
            $get_dept = $account_code_data[1] ? sanitize_text_field($account_code_data[1]) : '';
            $get_year = $account_code_data[2] ? strval($account_code_data[2]) : '';
            //@todo: add sanitize to year too?
            $account_code_cpt = array(
                'post_title' => sanitize_text_field($account_code),
                'post_type' => 'account_code',
                'post_status' => 'draft',
                'post_author' => 1,
            );

            $post_id = wp_insert_post($account_code_cpt);

            if (is_wp_error($post_id)) {
                // If any post fails, we’ll return false for now.
                // @todo: Improve error reporting.
                return false;
            }


            //terms
            $dept_term_id = term_exists($get_dept, 'department');

            error_log(print_r($dept_term_id, true));
            if ($dept_term_id) {
                wp_set_post_terms($post_id, $dept_term_id, 'department');
            }

            $budget_year_term_id = term_exists($get_year, 'budget_year');
            error_log(print_r($budget_year_term_id, true));
            if ($budget_year_term_id) {
                wp_set_post_terms($post_id, $get_year, 'budget_year');
            }

            if (!empty($get_budget)) {
                //postmeta (public)
                add_post_meta($post_id, 'budget_amount', $get_budget);
            }
        }

        return true;
    }
}
