// import allowedEmbedBlocks here, updated via settings page

wp.domReady(function () {

    const allowed_embed_blocks = [];
    
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

