// import allowedEmbedBlocks here, updated via settings page

// when dom is ready, assign blocks we want showing to array 
wp.domReady(function () {
    var allowed_embeds_array = [];
    
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/wp-content/plugins/hide_blocks_plugin/get_embed_blocks.php");
    xhr.send();
    // xhr.responseType = "json";
    xhr.onload = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {

            const data = xhr.response;
            const data_parsed = JSON.parse(data);

            const embeds_list = data_parsed['variation_array_text'];

            allowed_embeds_array = embeds_list.split(', ');
            allowed_embeds_array.forEach( (item) => {
                return item.trim();
            })
            console.log(allowed_embeds_array);
            // console.log(JSON.parse(embed_array));

        } else {
            console.log(`Error: ${xhr.status}`);
        }
        
        // get list of block variations for type "embed"
        const embedArr = wp.blocks.getBlockVariations('core/embed');
        console.log(embedArr);
        
        // for each item in embed variations... 
        embedArr.forEach(function (blockVariation) {
            //  if item doesn't exist in allowed blocks array, unregister from blocks 
            if (-1 === allowed_embeds_array.indexOf(blockVariation.name)) {
                wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
            }
        });
        
    };
});

