// wp.domReady(function () {
jQuery(document).ready(function ($) {
    // display all blocks code
    $blocksArr = [];
    wp.blocks.getBlockTypes().forEach( (blockType)=> {
        console.log(blockType.name);
        $blocksArr.push(blockType.name);
    });
    console.log($blocksArr);
    $blocks = $blocksArr.join(',');
    $.post('wp-content/plugins/hide_blocks_plugin/includes/hide-blocks-fields.php', {blocks: $blocks});
    //Users/bfarley/Local Sites/bret-multisite/app/public/wp-content/plugins/hide_blocks_plugin/includes/hide-blocks-fields.php
});
    
    // console.log(displayBlocksAsChecklist);
    // return displayBlocksAsChecklist();

// });

// jQuery(document).ready(function ($) {
//     $.ajax(
//         {
//             method: 'get',
//             url: allBlocksAjax.ajaxurl,
//             cache: false,
//             dataType: 'json',
//             data: {
//                 _ajax_nonce: my_ajax_obj.nonce,
//                 action: 'show_all_blocks'
//                 // more?
//             },
//             success: function (response) {
//                 $displayBlocksAsChecklist = () => {
//                     $blocksArr = [];
//                     wp.blocks.getBlockTypes().forEach( (blockType)=> {
//                         // console.log(blockType.name);
//                         $blocksArr.push(blockType.name);
//                         //console.log(blocksArr);
//                     });
//                     return $blocksArr;
//                 }
                
//                 // console.log(displayBlocksAsChecklist());
//                 return displayBlocksAsChecklist();
//             },
//             error: function() {
//                 console.log(err.Message);
//             }
//         }
//     );

// });
