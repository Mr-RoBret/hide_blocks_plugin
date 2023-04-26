// Code to hide main blocks

wp.domReady(function () {
    const allowed_main_blocks = [];

    fetch( '/wp-json/blocks-settings-main/v1/main-blocks', {
        method: 'GET',
        mode: 'cors',
        headers: {
            'Access-Control-Allow-Origin' : '*',
            'X-WP-Header' : 'nonce'
        }
    })
    .then(response => response.json())
    .then(data => console.log(data))
    .then(data => allowed_main_blocks.push(data))
    .catch(error => console.error(error));

    return allowed_main_blocks;

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
