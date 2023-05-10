
window.addEventListener('DOMContentLoaded', function () {
    wp.domReady(function () {
        async function getInserterList() {

            try {
                let blocks_arr = wp.data.select('core/block-editor').getInserterItems();
                
                // console.log("Success:", (blocks_arr));
                return blocks_arr;

            } catch (error) {
                console.error("Error:", error);
            }

        }
        const variations_all = getInserterList();
        // console.log(variations_all);
        return variations_all;
    });
})

