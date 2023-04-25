// import allowedEmbedBlocks here, updated via settings page

wp.domReady(function () {

    const allowed_embed_blocks = [];
    
    fetch('/wp-json/blocks-settings-main/v1/embed-blocks', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        console.log('data received:', data);
    })
    .catch(error => {
        //handle errors
        console.error(error);
        console.log('errored out');
    })
   
    // get list of block variations for type "embed"
    const embedArr = wp.blocks.getBlockTypes().map((block) => block.name);
    
    // for each item in embed variations... 
    embedArr.forEach( (block_type) => {
        // console.log(block_type);

        //  if item doesn't exist in allowed blocks array, unregister from blocks 
        if (allowed_embed_blocks.includes(block_type)) {
            console.log('not allowed, mr. ' + block_type + '!');
            wp.blocks.unregisterBlockType(block_type);
        }
    });
    
});

