// Code to hide main blocks

wp.domReady(function () {
    const allowed_main_blocks = [];
    
    // const url = '/wp-json/hide_blocks_plugin/v1/main-blocks';
    
    fetch('/wp-json/blocks-settings-main/v1/main-blocks', {
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
});   
    // get list of block variations for type "embed"
    const mainArr = wp.blocks.getBlockTypes().map((block) => block.name);
    
    // for each item in embed variations... 
    mainArr.forEach( (block_type) => {
        // console.log(block_type);

        //  if item doesn't exist in allowed blocks array, unregister from blocks 
        if (allowed_main_blocks.includes(block_type)) {
            console.log('not allowed, mr. ' + block_type + '!');
            wp.blocks.unregisterBlockType(block_type);
        }
    });
    
// });
