// script to add all blocks registered to array, then pass array back to PHP

jQuery(document).ready(function ($) {

    $blocksArr = ['first block name'];
    
    wp.blocks.getBlockTypes().forEach( (blockType) => {
        console.log(blockType.name);
        $blocksArr.push(blockType.name);
    });
    
    $blocks = $blocksArr.join(',');
    console.log($blocks);
    
    $.ajax({
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