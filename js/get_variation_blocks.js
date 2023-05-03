wp.domReady(function () {

    
    function getVariations () {
        
        const variations_arr = [];
        try {
            const blocks_arr = wp.blocks.getBlockTypes().map((block) => block.name);
            console.log(blocks_arr);
            
            // for(let block in blocks_arr) {
            //     let variations = wp.blocks.getBlockVariations(block);
            //     variations_arr.push(variations);
            // }
            
            console.log("Success:", (blocks_arr));
            return blocks_arr;
        } catch (error) {
            console.error("Error:", error);
        }
    }
    
    async function postVariations (variation_arr) {

        try {
            const response = await fetch( 'index.php/wp-json/blocks-settings-main/v1/variation-blocks', {
            method: 'POST',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
                'Access-Control-Allow-Origin' : '*',
                'X-WP-Header' : 'nonce'
            },
            body: JSON.stringify(variation_arr)
        });

        const result = await response.json();
            console.log("Success:", result);
        } catch (error) {
            console.error("Error:", error);
        }
    }

    const variation_arr = getVariations();
    console.log(postVariations(variation_arr));
    postVariations(variation_arr);

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