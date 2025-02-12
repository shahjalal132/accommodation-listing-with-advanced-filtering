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
        add_shortcode( 'list_accommodations', [ $this, 'list_accommodations_callback' ] );
    }

    public function list_accommodations_callback() {
        ob_start();
        ?>

        <div class="container mt-4">
            <div class="row">
                <!-- Accommodation Listings -->
                <div class="col-md-9">
                    <div class="row g-4">

                        <?php

                        // args for get accommodations 
                        $args = [
                            'post_type' => 'property',
                            'posts_per_page' => -1, 
                            'orderby'   => 'title',
                            'order'     => 'ASC',
                        ];

                        // get accommodations
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
                                    <div class="card common-shadow">
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
                                            <!-- room information -->
                                            <p class="mt-2"><i class="fa-solid fa-person"></i> <?= $total_guest ?> Guest &nbsp; <i
                                                    class="fa-solid fa-bed"></i> <?= $total_beds ?> Bed
                                                &nbsp; <i class="fa-solid fa-bath"></i> <?= $total_baths ?> Bath
                                                &nbsp; <i class="fa-solid fa-car"></i> <?= $total_cars ?> Car
                                            </p>
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
                <!-- Filter Sidebar -->
                <div class="col-md-3">
                    <div class="filter-sidebar">
                        <h5>Filters</h5>
                        <hr>
                        <h6>Bedroom Type</h6>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="1bedroom">
                            <label class="form-check-label" for="1bedroom">1-Bedroom</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="2bedroom">
                            <label class="form-check-label" for="2bedroom">2-Bedroom</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="3bedroom">
                            <label class="form-check-label" for="3bedroom">3-Bedroom</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="4bedroom">
                            <label class="form-check-label" for="4bedroom">4-Bedroom</label>
                        </div>
                        <hr>
                        <h6>View Type</h6>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="partial">
                            <label class="form-check-label" for="partial">Partial Ocean View</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="full">
                            <label class="form-check-label" for="full">Full Ocean View</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="garden">
                            <label class="form-check-label" for="garden">Garden View</label>
                        </div>
                        <hr>
                        <h6>Style & Features</h6>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="standard">
                            <label class="form-check-label" for="standard">Standard</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="superior">
                            <label class="form-check-label" for="superior">Superior</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="luxury">
                            <label class="form-check-label" for="luxury">Luxury</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="penthouse">
                            <label class="form-check-label" for="penthouse">Penthouse</label>
                        </div>
                        <hr>
                        <h6>Building Name</h6>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="azzure">
                            <label class="form-check-label" for="azzure">Azzure</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="oceanus">
                            <label class="form-check-label" for="oceanus">Oceanus</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="seanna">
                            <label class="form-check-label" for="seanna">Seanna</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="zinc">
                            <label class="form-check-label" for="zinc">Zinc</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php return ob_get_clean();
    }

}