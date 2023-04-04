// script to add all embed blocks registered to array, then pass array back to PHP

jQuery(document).ready(function ($) {

    $.ajax({
        type: "GET",
        // dataType: "json",
        url: '/wp-content/plugins/hide_blocks_plugin/get_embed_blocks.php',
        data: {
            action: 'get_embeds',
            embeds: ''
        },
        success: function(response){
            console.log('data received');
            console.log(response);
            $.ajax({
                type: "POST",
                // dataType: "json",
                url: myAjax.ajaxurl,
                data: {
                    action: 'display_embeds',
                    embeds: response
                },
                success: function(data){
                    console.log('data received');
                    console.log(data[1]);
                },
                error: function(data) {
                    console.log(data)
                    console.log('failed request');
                }
            });
            // console.log($embeds);
            // console.log(data);            
        },
        error: function(data) {
            console.log(data)
            console.log('failed request');
        }
    });
        
});
