// jQuery(document).ready(function ($) {
//     $blocksArr = ['first block name'];

//     wp.blocks.getBlockTypes().forEach( (blockType)=> {
//         console.log(blockType.name);
//         $blocksArr.push(blockType.name);
//     });

    
//     $blocks = $blocksArr.join(',');
//     console.log($blocks);

//     var data = {
//        'action': 'show_all_blocks',
//         'blocks' : $blocks
//     }

//     var xhr = new XMLHttpRequest();

//     // set the PHP page you want to send data to
//     xhr.open("POST", "wp-content/plugins/hide_blocks_plugin/includes/hide-blocks-fields.php", true);
//     xhr.setRequestHeader("Content-Type", "application/json");

//     // what to do when you receive a response
//     xhr.onreadystatechange = function () {
//         if (xhr.readyState == XMLHttpRequest.DONE) {
//             console.log(xhr.responseText);
//         }
//     };

//     // send the data
//     xhr.send(JSON.stringify(data));

// });

jQuery(document).ready(function ($) {
    $blocksArr = ['first block name'];

    wp.blocks.getBlockTypes().forEach( (blockType)=> {
        console.log(blockType.name);
        $blocksArr.push(blockType.name);
    });
    
    $blocks = $blocksArr.join(',');
    console.log($blocks);
    
    jQuery.ajax({
        type: "post",
        dataType: "text",
        url: myAjax.ajaxurl,
        data: {
            action:'show_all_blocks',
            blocks: $blocks
        },
        success: function(data){
            console.log('response received');
            console.log(data);
        },
        error: function(data) {
            console.log(data)
            console.log('failed request');
        }
    });
});