// import allowedEmbedBlocks here, updated via settings page

// when dom is ready, assign blocks we want showing to array 
wp.domReady(function () {
    
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/wp-content/plugins/hide_blocks_plugin/get_embed_blocks.php");
    xhr.send();
    xhr.responseType = "json";
    xhr.onload = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {

        const data = xhr.response;
        const embed_array = (data);

        console.log(embed_array);
        console.log(JSON.parse(embed_array));

    } else {
        console.log(`Error: ${xhr.status}`);
    }
};

    // for each item in block variations matching type 'core/embed'...
    const embedArr = wp.blocks.getBlockVariations('core/embed');
    // console.log(embedArr);
    embedArr.forEach(function (blockVariation) {
        //  if item doesn't exist in allowed blocks array, unregister from blocks 
        if (-1 === allowedEmbedBlocks.indexOf(blockVariation.name)) {
            wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
        }
    });
});

