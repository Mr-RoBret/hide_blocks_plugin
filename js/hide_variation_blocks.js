
async function getVariations() {
    try {
        const response = await fetch( '/index.php/wp-json/blocks-settings-main/v1/main-blocks', {
        method: 'GET',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*',
            'X-WP-Header' : 'nonce'
        }
    });
    const result = await response.json();
        // console.log(result);
        // const all_array = Object.values(result['all_variations']);

        // function to get parent block name and add as prefix (eg. "core/" + block.name)
        function addPrefix(parent, suffix) {
            const fullName = parent + "/" + suffix;
            console.log(fullName);
            return fullName;
        }

        const all_blocks_array = wp.blocks.getBlockTypes().map((block) => block.name);
        console.log(all_blocks_array);
        const all_variations_array = []

        all_blocks_array.forEach((block) => {
            const individual_block_vars = wp.blocks.getBlockVariations(block);
            console.log(block);
            console.log(individual_block_vars);

            const individual_names = individual_block_vars.map((var_name) => var_name.name);
            individual_names.forEach((block_var) => {
                
                block_full_name = addPrefix(block, block_var);

                all_variations_array.push(block_full_name);
            })
        })
        console.log(all_variations_array);

        const allowed_array = Object.values(result['allowed_variations']);

        all_variations_array.forEach((block_var) => {
            //  if item doesn't exist in allowed blocks array, unregister from blocks 
            if(!allowed_array.includes(block_var)) {
                block_var_name = block_var.split('/');
                block_name = block_var_name[block_var_name.length - 1];
                wp.blocks.unregisterBlockVariation('core/embed', block_name);
            }
        });
        
    } catch (error) {
        console.error("Error:", error);
    }
}

wp.domReady( getVariations );

