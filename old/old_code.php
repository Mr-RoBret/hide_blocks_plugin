/*
From hide-blocks-fields class="php" 
*/

// function display_embeds() {
//     if ( isset( $_GET['embeds'] ) ) {

//         echo $_GET['embeds'];
//         // $data = (json_decode($_GET['embeds']));
//         $data = $_GET['embeds'];
//         // var_dump(json_decode($data['blocks']));
//         // echo "stringified data, " . $data;
//         echo $data;
//         echo PHP_EOL;
//     }
// }

// // enqueue ajax script to obtain result from ajax call
// function ajax_script_enqueuer() {
//     wp_register_script( 'get_embed_blocks_admin', '/wp-content/plugins/hide_blocks_plugin/get_embed_blocks_admin.js', array('jquery', 'wp-blocks'), 100 );
//     wp_localize_script( 'get_embed_blocks_admin', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
    
//     wp_enqueue_script( 'jquery' );
//     wp_enqueue_script( 'get_embed_blocks_admin' );
// }


// add_action( 'admin_enqueue_scripts', 'ajax_script_enqueuer' );
// add_action( 'wp_ajax_display_embeds', 'display_embeds' );


