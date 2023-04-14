// Code to hide main blocks

// when dom is ready, assign blocks we want showing to array 
wp.domReady(function () {
    
    const test_url = '/wp-json/';
    
    fetch(test_url, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        console.log('API Index:', data);
    })
    .catch(error => {
        //handle errors
        console.log('api index error');
    })
}); 

wp.domReady(function () {
    const allowed_main_blocks = [];
    
    // const url = '/wp-json/hide_blocks_plugin/v1/main-blocks';
    
    fetch('/wp-json/hide_blocks_plugin/v1/main-blocks', {
        method: 'GET'
    })
    .then(response => response.json())
    .then(json => {
        console.log('data received:', json);
    })
    .catch(error => {
        //handle errors
        console.error(error);
        console.log('errored out');
    })
});   
    // get list of block variations for type "embed"
    // const mainArr = wp.blocks.getBlockTypes().map((block) => block.name);
    
    // // for each item in embed variations... 
    // mainArr.forEach( (block_type) => {
    //     // console.log(block_type);

    //     //  if item doesn't exist in allowed blocks array, unregister from blocks 
    //     if (allowed_main_blocks.includes(block_type)) {
    //         console.log('not allowed, mr. ' + block_type + '!');
    //         wp.blocks.unregisterBlockType(block_type);
    //     }
    // });
    
// });
