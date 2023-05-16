const all_variations_array = []

async function getAllVariations() {
    /**
     * function to get list of all block types, loop through each, and push any variations to a new array
    */
   
   // get all blocks from block type registry and map to array
   const all_blocks_array = wp.blocks.getBlockTypes().map((block) => block.name);
   
   // function to get parent block name and add as prefix (eg. "core/" + block.name)
   function addPrefix(parent, suffix) {
       const parent_arr = parent.split('/');
       const parent_block_name = parent_arr[parent_arr.length - 1];
       const fullName = parent_block_name + "/" + suffix;
       console.log(fullName);
       return fullName;
   }

   // for each block type, get variations
   all_blocks_array.forEach((block) => {
       const individual_block_vars = wp.blocks.getBlockVariations(block);

        // loop through variations and get array of names
        const individual_names = individual_block_vars.map((var_name) => var_name.name);

        // then, loop through names array and send to function for adding appropriate parent prefix
        individual_names.forEach((block_var) => {
            block_full_name = addPrefix(block, block_var);
            // finally, push properly formatted variation name to array
            all_variations_array.push(block_full_name);
        })
    })
    console.log(all_variations_array);
    return all_variations_array;
}

const result = getAllVariations();
console.log(result);

fetch( '/index.php/wp-json/blocks-settings-main/v1/variation-blocks', {
    method: 'POST',
    mode: 'cors',
    data: {
        all_variations_array: all_variations_array
    },
    headers: {
        'Content-Type': 'application/json',
    },
    // body: JSON.stringify({ result })
    body: result

// const result = await response.json();

})
.then(response => response.text())
.then(data => {
    console.log('response from PHP:', data);
}) 

.catch (error => {
    console.error("Error:", error);
})
