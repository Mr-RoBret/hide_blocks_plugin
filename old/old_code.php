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



// wp.domReady(function () {
//     var allowed_main_blocks = [];
    
//     const xhr = new XMLHttpRequest();
//     xhr.open("GET", "/wp-content/plugins/hide_blocks_plugin/get_main_blocks.php");
//     xhr.send();
//     // xhr.responseType = "text";
//     xhr.onload = () => {
//         if (xhr.readyState == 4 && xhr.status == 200) {

//             const data = xhr.response;
//             console.log(JSON.parse(data));

//             const data_parsed = JSON.parse(data);
//             const object_from_parsed = JSON.parse(data_parsed);
//             // console.log(object_from_parsed['array_text']);

//             const array_from_parsed = object_from_parsed['array_text'].split(', ');
//             // console.log(array_from_parsed);

//             for (let i in array_from_parsed) { 
//                 allowed_main_blocks.push(array_from_parsed[i]); 
//             };
//             // console.log(allowed_main_blocks);

//         } else {
//             console.log(`Error: ${xhr.status}`);
//         }
        
//         // get list of block variations for type "embed"
//         const mainArr = wp.blocks.getBlockTypes().map((block) => block.name);
        
//         // console.log('mainArr is ' + mainArr);
//         // for each item in embed variations... 
//         mainArr.forEach( (block_type) => {
//             // console.log(block_type);
//             //  if item doesn't exist in allowed blocks array, unregister from blocks 
//             if (allowed_main_blocks.includes(block_type)) {
//                 console.log('not allowed, mr. ' + block_type + '!');
//                 wp.blocks.unregisterBlockType(block_type);
//             }
//         });
        
//     };
// });