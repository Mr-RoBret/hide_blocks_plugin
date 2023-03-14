<?php 

require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

function get_embeds() {
    $embed_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    // $embed_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $embed_names = array();

    foreach( $embed_types as $key ) {
        $embed_names[] = $key->name;
    }

    print_r( 'sending json!' );
    print_r($embed_names);
    echo $embed_names;
    return json_encode($embed_names);
    // return $embed_names;
}

add_action( 'admin_init', 'get_embeds' );

?>