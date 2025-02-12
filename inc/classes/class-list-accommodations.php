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

        <h1>Accommodations</h1>

        <?php return ob_get_clean();
    }

}