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
            console.log(result);
            console.log(result);
            
            console.log("Success:", result);
            return result;
        } catch (error) {
            console.error("Error:", error);
        }
    }

    function hideVariations(variations_array) {
        variations_array.forEach((block_type) => {
            // console.log(block_type);
            //  if item doesn't exist in allowed blocks array, unregister from blocks 
            if (allowed_variations.includes(block_type)) {
                console.log('not allowed, mr. ' + block_type + '!');
                wp.blocks.unregisterBlockType(block_type);
            }
        });
    }

    const variations_to_hide_arr = getVariations();
    console.log(hideVariations(variations_to_hide_arr));

    // $.ajax({
    //     type: "POST",
    //     url: "hide_blocks_plugin.php", // The PHP script that will process the data
    //     data: { result: variation_arr }, // The data to be sent to the server
    //     success: function(response) {
    //         console.log("Data sent successfully");
    //         console.log(response);
    //     }
    // });
});