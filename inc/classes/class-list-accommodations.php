<?php

namespace BOILERPLATE\Inc;

use BOILERPLATE\Inc\Traits\Credentials_Options;
use BOILERPLATE\Inc\Traits\Program_Logs;
use BOILERPLATE\Inc\Traits\Singleton;

class List_Accommodations {

    use Singleton;
    use Program_Logs;
    use Credentials_Options;

    public function __construct() {
        // $this->load_credentials_options();
        $this->setup_hooks();
    }

    public function setup_hooks() {
        // list accommodation shortcode
        add_shortcode( 'list_accommodations', [ $this, 'list_accommodations_callback' ] );

        // handle filter ajax request
        add_action( 'wp_ajax_filter_accommodations', [ $this, 'filter_accommodations_callback' ] );
        add_action( 'wp_ajax_nopriv_filter_accommodations', [ $this, 'filter_accommodations_callback' ] );

        // get all property types (taxonomy)
        // add_action( 'init', [ $this, 'get_property_types' ] );

    }

    public function get_property_types() {

        $terms = get_terms( [
            'taxonomy'   => 'property_type', // Custom taxonomy
            'hide_empty' => false, // Include empty terms
        ] );

        $taxonomy_list = [];

        if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $taxonomy_list[] = [
                    'name' => $term->name,
                    'slug' => $term->slug,
                ];
            }
        }

        // log the property list
        put_program_logs( 'property_list: ' . json_encode( $taxonomy_list ) );

    }

    function filter_accommodations_callback() {
        if ( isset( $_POST['filters'] ) ) {

            // get filters values
            $filters = $_POST['filters'];

            if ( !empty( $filters ) ) {
                // get filtered accommodations
                echo $this->get_filtered_accommodations( $filters );
            }

        } else {
            // get all accommodations
            echo $this->get_filtered_accommodations( [] );
        }
        wp_die();
    }

    public function get_filtered_accommodations( $filters ) {

        // Default query arguments
        $args = [
            "post_type"      => "property",
            "posts_per_page" => -1,
            "orderby"        => "title",
            "order"          => "ASC",
        ];

        // Set args for filters
        if ( !empty( $filters ) ) {
            $args["tax_query"] = [
                [
                    "taxonomy" => "property_type",
                    "field"    => "slug",
                    "terms"    => $filters,
                    "operator" => "IN",
                ],
            ];
        }

        // put_program_logs( 'args: ' . json_encode( $args ) );

        // Get accommodations based on query
        $properties = new \WP_Query( $args );

        // get how many accommodations found
        $total_accommodations = $properties->found_posts;
        // put_program_logs( 'total_accommodations: ' . $total_accommodations );

        ob_start();
        ?>

        <div class="row g-4">

            <?php
            // If there are accommodations, display them
            if ( $properties->have_posts() ) :
                while ( $properties->have_posts() ) :
                    $properties->the_post();

                    // get post id
                    $post_id = get_the_ID();

                    // get post thumbnail
                    $_thumbnail            = get_the_post_thumbnail_url( $post_id );
                    $placeholder_thumbnail = PLUGIN_PUBLIC_ASSETS_URL . '/images/600x400.png';
                    $thumbnail             = $_thumbnail ? $_thumbnail : $placeholder_thumbnail;

                    // get post permalink
                    $permalink = get_the_permalink( $post_id );

                    // get post title
                    $title = get_the_title( $post_id );
                    // get address
                    $address = get_post_meta( $post_id, 'Address', true );
                    // get total guest
                    $total_guest = get_post_meta( $post_id, 'total_guests', true );
                    // get total bed
                    $total_beds = get_post_meta( $post_id, 'bedrooms', true );
                    // get total bath
                    $total_baths = get_post_meta( $post_id, 'bathrooms', true );
                    // get total car
                    $total_cars = get_post_meta( $post_id, 'car_park', true );
                    // get price
                    $price = get_post_meta( $post_id, 'price', true );

                    ?>

                    <div class="col-md-4">
                        <!-- start: accommodation card -->
                        <div class="card common-shadow h-515">
                            <!-- thumbnail image -->
                            <a href="<?= $permalink ?>"><img src="<?= $thumbnail ?>" class="card-img-top" alt="<?= $title ?>"></a>
                            <div class="card-body">
                                <!-- title -->
                                <h5 class="card-title"><a href="<?= $permalink ?>"
                                        class="text-dark text-decoration-none"><?= $title ?></a>
                                </h5>
                                <!-- address -->
                                <a href="<?= $permalink ?>" class="mt-3 mb-3"><?= $address ?></a>
                                <hr class="mt-4">
                                <!-- room information -->
                                <p class="mt-3 mb-3 cfs-15"><i class="fa-solid fa-person"></i> <?= $total_guest ?> Guest
                                    &nbsp; <i class="fa-solid fa-bed"></i> <?= $total_beds ?> Bed
                                    &nbsp; <i class="fa-solid fa-bath"></i> <?= $total_baths ?> Bath
                                    &nbsp; <i class="fa-solid fa-car"></i> <?= $total_cars ?> Car
                                </p>
                                <hr class="mb-4">
                                <!-- price -->
                                <a href="<?= $permalink ?>" class=""><strong>From $<?= $price ?> per
                                        night</strong></a>
                            </div>
                        </div>
                        <!-- end: accommodation card -->
                    </div>

                <?php endwhile;
            else :
                echo "<p>No accommodations found.</p>";
            endif;

            ?>
        </div>

        <?php return ob_get_clean();

    }

    public function list_accommodations_callback() {
        ob_start();
        ?>

        <div class="container-fluid mt-4">
            <div class="row">

                <!-- Filter Sidebar -->
                <div class="col-md-3">
                    <div class="search-bar-section">
                        <h5>Search</h5>
                        <input type="text" class="form-control mt-1" id="accommodation-search" placeholder="Search accommodations">
                    </div>
                    <div class="filters-items">
                        <?php include_once PLUGIN_BASE_PATH . '/inc/components/filters.php' ?>
                    </div>
                </div>

                <!-- Accommodation Listings -->
                <div class="col-md-9">
                    <div id="accommodations-wrapper">
                        <div class="row g-4">

                            <?php

                            // Default query arguments
                            $args = [
                                'post_type'      => 'property',
                                'posts_per_page' => -1,
                                'orderby'        => 'title',
                                'order'          => 'ASC',
                            ];

                            // Get accommodations based on query
                            $properties = new \WP_Query( $args );

                            // if there are accommodations
                            if ( $properties->have_posts() ) :
                                while ( $properties->have_posts() ) :
                                    $properties->the_post();

                                    // get post id
                                    $post_id = get_the_ID();

                                    // get post thumbnail
                                    $_thumbnail            = get_the_post_thumbnail_url( $post_id );
                                    $placeholder_thumbnail = PLUGIN_PUBLIC_ASSETS_URL . '/images/600x400.png';
                                    $thumbnail             = $_thumbnail ? $_thumbnail : $placeholder_thumbnail;

                                    // get post permalink
                                    $permalink = get_the_permalink( $post_id );

                                    // get post title
                                    $title = get_the_title( $post_id );
                                    // get address
                                    $address = get_post_meta( $post_id, 'Address', true );
                                    // get total guest
                                    $total_guest = get_post_meta( $post_id, 'total_guests', true );
                                    // get total bed
                                    $total_beds = get_post_meta( $post_id, 'bedrooms', true );
                                    // get total bath
                                    $total_baths = get_post_meta( $post_id, 'bathrooms', true );
                                    // get total car
                                    $total_cars = get_post_meta( $post_id, 'car_park', true );
                                    // get price
                                    $price = get_post_meta( $post_id, 'price', true );

                                    ?>

                                    <div class="col-md-4">
                                        <!-- start: accommodation card -->
                                        <div class="card common-shadow h-515">
                                            <!-- thumbnail image -->
                                            <a href="<?= $permalink ?>"><img src="<?= $thumbnail ?>" class="card-img-top"
                                                    alt="<?= $title ?>"></a>
                                            <div class="card-body">
                                                <!-- title -->
                                                <h5 class="card-title"><a href="<?= $permalink ?>"
                                                        class="text-dark text-decoration-none"><?= $title ?></a>
                                                </h5>
                                                <!-- address -->
                                                <a href="<?= $permalink ?>" class="mt-3 mb-3"><?= $address ?></a>
                                                <hr class="mt-4">
                                                <!-- room information -->
                                                <p class="mt-3 mb-3 cfs-15"><i class="fa-solid fa-person"></i> <?= $total_guest ?> Guest
                                                    &nbsp; <i class="fa-solid fa-bed"></i> <?= $total_beds ?> Bed
                                                    &nbsp; <i class="fa-solid fa-bath"></i> <?= $total_baths ?> Bath
                                                    &nbsp; <i class="fa-solid fa-car"></i> <?= $total_cars ?> Car
                                                </p>
                                                <hr class="mb-4">
                                                <!-- price -->
                                                <a href="<?= $permalink ?>" class=""><strong>From $<?= $price ?> per
                                                        night</strong></a>
                                            </div>
                                        </div>
                                        <!-- end: accommodation card -->
                                    </div>

                                <?php endwhile;
                            endif;

                            ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php return ob_get_clean();
    }

}