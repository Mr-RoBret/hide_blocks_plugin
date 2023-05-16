
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
            console.log(result);
        return result;    
    } catch (error) {
        console.error("Error:", error);
    }
}

async function getAllVariations() {
    try {
        const response = await fetch( '/index.php/wp-json/blocks-settings-main/v1/variation-blocks', {
        method: 'GET',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*',
            'X-WP-Header' : 'nonce'
        }
    });
    const result = await response.json();
        console.log(result);
        
        
        const all_variations_array = Object.values(result['all_variations']);
        // next, get array of allowed variations from returned call to php file (which grabs list from wp sitemeta option)
        const allowed_array = Object.values(result['allowed_variations']);
        
        //for each item in all variations array...
        all_variations_array.forEach((block_var) => {
            //  if item doesn't exist in allowed blocks array, unregister from blocks 
            if(!allowed_array.includes(block_var)) {
                // get variation name only and if not in whitelist option array, unregister
                block_var_name = block_var.split('/');
                block_name = block_var_name[block_var_name.length - 1];
                wp.blocks.unregisterBlockVariation('core/embed', block_name);
            }
        });
        return all_variations_array;
    } catch (error) {
        console.error("Error:", error);
    }
}

const allowed_result = getVariations();
const all_result = getAllVariations();

const all_variations_array = Object.values(all_result['all_variations']);
// next, get array of allowed variations from returned call to php file (which grabs list from wp sitemeta option)
const allowed_array = Object.values(allowed_result['allowed_variations']);

//for each item in all variations array...
all_variations_array.forEach((block_var) => {
    //  if item doesn't exist in allowed blocks array, unregister from blocks 
    if(!allowed_array.includes(block_var)) {
        // get variation name only and if not in whitelist option array, unregister
        block_var_name = block_var.split('/');
        block_name = block_var_name[block_var_name.length - 1];
        wp.blocks.unregisterBlockVariation('core/embed', block_name);
    }
});
        



wp.domReady( getVariations );

