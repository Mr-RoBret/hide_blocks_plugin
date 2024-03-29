
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

        const all_array = Object.values(result['all_variations']);
        const allowed_array = Object.values(result['allowed_variations']);

        all_array.forEach((block_var) => {
            block_var_name = block_var.split('/'); // split full name
            block_var_prefix = block_var_name[0];   // get prifix (ie 'core/')
            block_name = block_var_name[block_var_name.length - 1]; // get slug

            //  if item doesn't exist in allowed blocks array AND if block_var starts with ('core/embed'),
            //  unregister from blocks
            if(!allowed_array.includes(block_var) && block_var_prefix === 'embed') { 
                wp.blocks.unregisterBlockVariation('core/embed', block_name);
            }
        });
        
    } catch (error) {
        console.error("Error:", error);
    }
}

wp.domReady( getVariations );

