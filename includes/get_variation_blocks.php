<?php 
    $whitelisted_variations = 'variation_checkbox_options';

    function multisite_settings_get_variations() {
        
        $all_variations_arr = array(
            'embed/twitter' => 'embed/twitter',
            'embed/youtube' => 'embed/youtube',
            'embed/instagram' => 'embed/instagram',
            'embed/wordpress' => 'embed/wordpress',
            'embed/soundcloud' => 'embed/soundcloud',
            'embed/spotify' => 'embed/spotify',
            'embed/flickr' => 'embed/flickr',
            'embed/vimeo' => 'embed/vimeo',
            'embed/animoto' => 'embed/animoto',
            'embed/cloudup' => 'embed/cloudup',
            'embed/collegehumor' => 'embed/collegehumor',
            'embed/crowdsignal' => 'embed/crowdsignal',
            'embed/dailymotion' => 'embed/dailymotion',
            'embed/imgur' => 'embed/imgur',
            'embed/issuu' => 'embed/issuu',
            'embed/kickstarter' => 'embed/kickstarter',
            'embed/mixcloud' => 'embed/mixcloud',
            'embed/pocketcasts' => 'embed/pocketcasts',
            'embed/reddit' => 'embed/reddit',
            'embed/reverbnation' => 'embed/reverbnation',
            'embed/screencast' => 'embed/screencast',
            'embed/scribd' => 'embed/scribd',
            'embed/slideshare' => 'embed/slideshare',
            'embed/smugmug' => 'embed/smugmug',
            'embed/speaker-deck' => 'embed/speaker-deck',
            'embed/tiktok' => 'embed/tiktok',
            'embed/ted' => 'embed/ted',
            'embed/tumblr' => 'embed/tumblr',
            'embed/videopress' => 'embed/videopress',
            'embed/wordpress-tv' => 'embed/wordpress-tv',
            'embed/amazon-kindle' => 'embed/amazon-kindle',
            'embed/pinterest' => 'embed/pinterest',
            'embed/wolfram-cloud' => 'embed/wolfram-cloud',
            'embed/facebook' => 'embed/facebook',
            'embed/loom' => 'embed/loom',
            'embed/smartframe' => 'embed/smartframe',
            'embed/descript' => 'embed/descript'
        );

        $all_variations = $all_variations_arr;

        $allowed_variations = get_site_option($GLOBALS [ 'whitelisted_variations' ]);
        
        //return both 'all' and 'allowed' arrays
        return rest_ensure_response( 
            [ 
            'all_variations' => $all_variations,
            'allowed_variations' => $allowed_variations
            ] );
    }

    // function get_permissions_callback() {
    //     if ( ! current_user_can( 'manage_network_options' ) ) {
    //         return new WP_Error( 'rest_forbidden ', 
    //         esc_html__( 'OMG you cannot view private data.', 
    //         'multisite-settings' ), 
    //         array( 'status' => 401 ) );
    //     }
    //     return true;
    // }

    function multisite_settings_register_api_routes() {

        register_rest_route( 'blocks-settings-main/v1', '/main-blocks', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'multisite_settings_get_variations',
            // 'permission_callback' => 'get_permissions_callback'
        ) );

    }

    add_action('rest_api_init', 'multisite_settings_register_api_routes');