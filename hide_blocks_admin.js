
// function getBlockVariations() {
    // when dom is ready, assign blocks we want showing to array 
    wp.domReady(function () {
        const allowedEmbedBlocks = [
            'vimeo',
            'youtube',
        ];

        // for each item in block variations matching type 'core/embed'...
        const embedArr = wp.blocks.getBlockVariations('core/embed');
        console.log(embedArr);
        embedArr.forEach(function (blockVariation) {
            //  if item doesn't exist in allowed blocks array, unregister from blocks 
            if (-1 === allowedEmbedBlocks.indexOf(blockVariation.name)) {
                wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
            }
        });
    });
// }    

// getBlockVariations;