<?php

namespace BOILERPLATE\Inc;

use BOILERPLATE\Inc\Traits\Credentials_Options;
use BOILERPLATE\Inc\Traits\Program_Logs;
use BOILERPLATE\Inc\Traits\Singleton;

class Category_Taxonomy {

    use Singleton;
    use Program_Logs;
    use Credentials_Options;

    public function __construct() {
        // $this->load_credentials_options();
        $this->setup_hooks();
    }

    public function setup_hooks() {
        // Hook into init action
        add_action( 'init', [ $this, 'register_property_taxonomies' ] );
    }

    public function register_property_taxonomies() {
        // Labels for the custom taxonomy
        $labels = array(
            'name'              => _x( 'Property Categories', 'taxonomy general name', 'lawaf' ),
            'singular_name'     => _x( 'Property Category', 'taxonomy singular name', 'lawaf' ),
            'search_items'      => __( 'Search Categories', 'lawaf' ),
            'all_items'         => __( 'All Categories', 'lawaf' ),
            'parent_item'       => __( 'Parent Category', 'lawaf' ),
            'parent_item_colon' => __( 'Parent Category:', 'lawaf' ),
            'edit_item'         => __( 'Edit Category', 'lawaf' ),
            'update_item'       => __( 'Update Category', 'lawaf' ),
            'add_new_item'      => __( 'Add New Category', 'lawaf' ),
            'new_item_name'     => __( 'New Category Name', 'lawaf' ),
            'menu_name'         => __( 'Categories', 'lawaf' ),
        );

        // Register the taxonomy
        register_taxonomy( 'property_category', array( 'property' ), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'property-category' ),
        ) );
    }

}