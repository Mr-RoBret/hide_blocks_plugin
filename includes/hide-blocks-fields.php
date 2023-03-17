<?php 

function get_all_blocks() {
    $test_regex = "/[a-z]+\/[a-z]+-?[a-z]+$/";
    // $prefix_regex = "/[a-z]+\-?[a-z]+$/";
    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $block_names = array();
    foreach( $block_types as $key ) {
        $block_names[] = $key->name;
    }
    $block_names_verified = array();
    foreach( $block_names as $name ) {
        $success = preg_match( $test_regex, $name, $match );
        if( $success ) {
            // preg_match( $prefix_regex, $name, $matches );
            array_push( $block_names_verified, $name ); // echo '<p>' . $matches[0] . ', </p></br>';
            // print_r( $matches[0] );
        }
    }
    // print_r( $block_names_truncated );
    return $block_names_verified;
}

function main_markup() {

    echo '<p><strong>List of Blocks Currently Registered:</strong></p>';
    echo '<br>';
        echo '<div id="list-blocks">';
            $main_blocks_registry = get_all_blocks();
            foreach( $main_blocks_registry as $registered_block ) {
                echo '*' . $registered_block . '* ';
            }
        echo '</div>';

    $options = get_option( 'blocks_settings_main' );

    $array_text = '';
    if( isset( $options[ 'array_text' ] ) ) {
        $array_text = esc_html( $options[ 'array_text' ] );
    }

    echo '<br><br>
        <textarea id="hideblocks_array_text" 
        name="blocks_settings_main[array_text]" 
        rows="5" cols="50">' . $array_text . '</textarea>';
}

function variation_markup() {
    echo '<p><strong>List of Embed Blocks Currently Registered:</strong></p>';
    echo '<br>';
        
    $options = get_option( 'blocks_settings_embed' );

    $variation_array_text = '';
    if( isset( $options[ 'variation_array_text' ] ) ) {
        $variation_array_text = esc_html( $options[ 'variation_array_text' ] );
    }

    echo '<textarea id="hideblocks_variation_array_text" name="blocks_settings_embed[variation_array_text]" rows="5" cols="50">' . $variation_array_text . '</textarea>';
}

function blocks_settings() {
    // If plugin settings don't exist, create them
    if( false == get_option( 'blocks_settings_main' ) ) {
        add_option( 'blocks_settings_main' );
    }
    if( false == get_option( 'blocks_settings_embed' ) ) {
        add_option( 'blocks_settings_embed' );
    }
    
    add_settings_section(
        // unique identifier for section
        'select_blocks_section',
        // section title
        __( 'Main Blocks to Hide', 'hideblocks' ),
        // Callback for optional description
        'add_instructions',
        // Admin page to add section to
        'hide-blocks'
    );

    add_settings_section(
        // unique identifier for section
        'select_embeds_section',
        // section title
        __( 'Embed Blocks to Show', 'hideblocks' ),
        // Callback for optional description
        'add_embed_instructions',
        // Admin page to add section to
        'hide-blocks'
    );

    // text field for main blocks array
    add_settings_field(
        // unique identifier for field
        'text-field-id',
        // field title
        __( 'Main Blocks Array', 'hideblocks' ),
        // callback for field markup
        'main_markup',
        // page to place on
        'hide-blocks',
        //section to place option in
        'select_blocks_section',
    );

    // text field for variation blocks array
    add_settings_field(
        // unique identifier for field
        'text-field-variation-id',
        // field title
        __( 'Embed Blocks Array', 'hideblocks' ),
        // callback for field markup
        'variation_markup',
        // page to place on
        'hide-blocks',
        //section to place option in
        'select_embeds_section',
    );

    register_setting(
        'settings_both_blocks', // group (correct name?)
        'blocks_settings_main'  // specific setting we are registering
    );
    register_setting(
        'settings_both_blocks', // group (correct name?)
        'blocks_settings_embed'  // specific setting we are registering
    );
}

function add_instructions() {
    echo ("<p>Add names of blocks you would like to hide from the Block Selector.</p>");
}

function add_embed_instructions() {
    echo ("<p>Add names of EMBED blocks you would like SHOWING in the Block Selector.</p>");
}

add_action( 'admin_init', 'blocks_settings' );
add_action( 'wp_loaded', 'get_all_blocks' );

?>
