<?php

namespace Rachievee\SpreadsheetPostConverter\Admin\Services;

class Structure
{
    public function register(){
        $this->register_account_post_type();
        $this->register_account_taxonomies();
        $this->add_account_terms();
    }

    private function register_account_post_type(){
        $labels = array(
            'name' => _x('Account Codes', 'Post Type General Name', 'spreadsheet-post-converter'),
            'singular_name' => _x('Account Code', 'Post Type Singular Name', 'spreadsheet-post-converter'),
            'menu_name' => __('Account Codes', 'spreadsheet-post-converter'),
            'name_admin_bar' => __('Account Code', 'spreadsheet-post-converter'),
            'attributes' => __('Account Code Attributes', 'spreadsheet-post-converter'),
            'parent_item_colon' => __('Parent Item:', 'spreadsheet-post-converter'),
            'all_items' => __('All Account Codes', 'spreadsheet-post-converter'),
            'add_new_item' => __('Add New Account Code', 'spreadsheet-post-converter'),
            'add_new' => __('Add New', 'spreadsheet-post-converter'),
            'new_item' => __('New Account Code', 'spreadsheet-post-converter'),
            'edit_item' => __('Edit Account Code', 'spreadsheet-post-converter'),
            'update_item' => __('Update Account Code', 'spreadsheet-post-converter'),
            'view_item' => __('View Account Code', 'spreadsheet-post-converter'),
            'view_items' => __('View Account Codes', 'spreadsheet-post-converter'),
            'search_items' => __('Search Account Code', 'spreadsheet-post-converter'),
            'not_found' => __('Not found', 'spreadsheet-post-converter'),
            'not_found_in_trash' => __('Not found in Trash', 'spreadsheet-post-converter'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'spreadsheet-post-converter'),
            'items_list' => __('Account code list', 'spreadsheet-post-converter'),
            'items_list_navigation' => __('Account code list navigation', 'spreadsheet-post-converter'),
            'filter_items_list' => __('Filter account code list', 'spreadsheet-post-converter'),
        );
        $args = array(
            'label' => __('Account Code', 'spreadsheet-post-converter'),
            'description' => __('Account Codes', 'spreadsheet-post-converter'),
            'labels' => $labels,
            'supports' => array('title', 'custom-fields'),
            'taxonomies' => array('department', ' budget_year'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-media-text',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => false,
        );

        register_post_type('account_code', $args);
    }

    private function register_account_taxonomies(){
        register_taxonomy('department', array('account_code'), $this->get_department_args());
        register_taxonomy('budget_year', array('account_code'), $this->get_budget_year_args());
    }

    private function get_department_args() : array {
        $labels = array(
            'name' => _x('Departments', 'Taxonomy General Name', 'spreadsheet-post-converter'),
            'singular_name' => _x('Department', 'Taxonomy Singular Name', 'spreadsheet-post-converter'),
            'menu_name' => __('Department', 'spreadsheet-post-converter'),
            'all_items' => __('All Departments', 'spreadsheet-post-converter'),
            'parent_item' => __('Parent Item', 'spreadsheet-post-converter'),
            'parent_item_colon' => __('Parent Item:', 'spreadsheet-post-converter'),
            'new_item_name' => __('New Department', 'spreadsheet-post-converter'),
            'add_new_item' => __('Add New Department', 'spreadsheet-post-converter'),
            'edit_item' => __('Edit Department', 'spreadsheet-post-converter'),
            'update_item' => __('Update Department', 'spreadsheet-post-converter'),
            'view_item' => __('View Department', 'spreadsheet-post-converter'),
            'separate_items_with_commas' => __('Separate departments with commas', 'spreadsheet-post-converter'),
            'add_or_remove_items' => __('Add or remove departments', 'spreadsheet-post-converter'),
            'choose_from_most_used' => __('Choose from the most used', 'spreadsheet-post-converter'),
            'popular_items' => __('Popular Departments', 'spreadsheet-post-converter'),
            'search_items' => __('Search Departments', 'spreadsheet-post-converter'),
            'not_found' => __('Not Found', 'spreadsheet-post-converter'),
            'no_terms' => __('No Departments', 'spreadsheet-post-converter'),
            'items_list' => __('Department list', 'spreadsheet-post-converter'),
            'items_list_navigation' => __('Department list navigation', 'spreadsheet-post-converter'),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud' => false,
        );

        return $args;
    }
    private function get_budget_year_args() : array {
        $labels = array(
            'name' => _x('Budget Years', 'Taxonomy General Name', 'spreadsheet-post-converter'),
            'singular_name' => _x('Budget Year', 'Taxonomy Singular Name', 'spreadsheet-post-converter'),
            'menu_name' => __('Budget Year', 'spreadsheet-post-converter'),
            'all_items' => __('All Years', 'spreadsheet-post-converter'),
            'parent_item' => __('Parent Item', 'spreadsheet-post-converter'),
            'parent_item_colon' => __('Parent Item:', 'spreadsheet-post-converter'),
            'new_item_name' => __('New Budget Year', 'spreadsheet-post-converter'),
            'add_new_item' => __('Add New Budget Year', 'spreadsheet-post-converter'),
            'edit_item' => __('Edit Budget Year', 'spreadsheet-post-converter'),
            'update_item' => __('Update Budget Year', 'spreadsheet-post-converter'),
            'view_item' => __('View Budget Year', 'spreadsheet-post-converter'),
            'separate_items_with_commas' => __('Separate budget years with commas', 'spreadsheet-post-converter'),
            'add_or_remove_items' => __('Add or remove budget years', 'spreadsheet-post-converter'),
            'choose_from_most_used' => __('Choose from the most used', 'spreadsheet-post-converter'),
            'popular_items' => __('Popular Budget Years', 'spreadsheet-post-converter'),
            'search_items' => __('Search Budget Years', 'spreadsheet-post-converter'),
            'not_found' => __('Not Found', 'spreadsheet-post-converter'),
            'no_terms' => __('No Budget Years', 'spreadsheet-post-converter'),
            'items_list' => __('Budget year list', 'spreadsheet-post-converter'),
            'items_list_navigation' => __('Year list navigation', 'spreadsheet-post-converter'),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud' => false,
        );

        return $args;
    }

    private function add_account_terms() : void {
        $dept_term_exists = $this->does_taxonomy_exist('department');
        if ($dept_term_exists) {

        }
    }
    
    private function does_taxonomy_exist( string $term_slug ) : bool {
        return taxonomy_exists($term_slug);
    }
}
