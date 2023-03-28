// Code to hide main blocks

// when dom is ready, assign blocks we want showing to array 
wp.domReady(function () {
    var allowed_main_blocks = [];
    
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/wp-content/plugins/hide_blocks_plugin/get_main_blocks.php");
    xhr.send();
    // xhr.responseType = "text";
    xhr.onload = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {

            const data = xhr.response;
            console.log(JSON.parse(data));

            const data_parsed = JSON.parse(data);
            const object_from_parsed = JSON.parse(data_parsed);
            // console.log(object_from_parsed['array_text']);

            const array_from_parsed = object_from_parsed['array_text'].split(', ');
            // console.log(array_from_parsed);

            for (let i in array_from_parsed) { 
                allowed_main_blocks.push(array_from_parsed[i]); 
            };
            // console.log(allowed_main_blocks);

        } else {
            console.log(`Error: ${xhr.status}`);
        }
        
        // get list of block variations for type "embed"
        const mainArr = wp.blocks.getBlockTypes().map((block) => block.name);
        
        // console.log('mainArr is ' + mainArr);
        // for each item in embed variations... 
        mainArr.forEach( (block_type) => {
            // console.log(block_type);
            //  if item doesn't exist in allowed blocks array, unregister from blocks 
            if (allowed_main_blocks.includes(block_type)) {
                console.log('not allowed, mr. ' + block_type + '!');
                wp.blocks.unregisterBlockType(block_type);
            }
        });
        
    };
});