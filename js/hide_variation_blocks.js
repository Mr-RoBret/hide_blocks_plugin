window.addEventListener('DOMContentLoaded', function () {

    wp.domReady(function () {
        
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
                // console.log(all_array);
                const allowed_array = Object.values(result['allowed_variations']);
                // console.log(allowed_array);

                all_array.forEach((block_var) => {

                    //  if item doesn't exist in allowed blocks array, unregister from blocks 
                    if(!allowed_array.includes(block_var)) {
                        // console.log('not allowed, mr. ' + block_var + '!');
                        wp.blocks.unregisterBlockType(block_var);
                    }
                    console.log(block_var);
                });

                console.log("Success:", result);
                return result;
                
            } catch (error) {
                console.error("Error:", error);
            }
        }

        const variations_all = getVariations();
        
        return variations_all;

    });

})

