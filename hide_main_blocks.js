// Code to hide main blocks

// when dom is ready, assign blocks we want showing to array 
wp.domReady(function () {
    var allowed_main_blocks = [];
    
    const url = '/wp-content/plugins/hide_blocks_plugin/get_main_blocks.php';
    
    fetch(url, {
        method: "GET"
    })
    .then(response => response.json())
    .then(json => {
        console.log('parsed json', json)
    })
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        //handle errors
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
