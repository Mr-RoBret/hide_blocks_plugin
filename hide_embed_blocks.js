// import allowedEmbedBlocks here, updated via settings page

// when dom is ready, assign blocks we want showing to array 
// wp.domReady(function () {
//     var allowed_embeds_array = [];
    
//     const xhr = new XMLHttpRequest();
//     xhr.open("GET", "/wp-content/plugins/hide_blocks_plugin/get_embed_blocks.php");
//     xhr.send();
//     // xhr.responseType = "json";
//     xhr.onload = () => {
//         if (xhr.readyState == 4 && xhr.status == 200) {

//             const data = xhr.response;
//             console.log(data);

//             const data_parsed = JSON.parse(data);
//             const object_from_parsed = JSON.parse(data_parsed);
//             // console.log(object_from_parsed['variation_array_text']);

//             const array_from_parsed = object_from_parsed['variation_array_text'].split(', ');
//             // console.log(array_from_parsed);

//             for (let i in array_from_parsed) { 
//                 allowed_embeds_array.push(array_from_parsed[i]); 
//             };
//             // console.log(allowed_embeds_array);
            

//         } else {
//             console.log(`Error: ${xhr.status}`);
//         }
        
//         // get list of block variations for type "embed"
//         const embedArr = wp.blocks.getBlockVariations('core/embed');
//         // console.log(embedArr);
        
//         // for each item in embed variations... 
//         embedArr.forEach(function (blockVariation) {
//             //  if item doesn't exist in allowed blocks array, unregister from blocks 
//             if (-1 === allowed_embeds_array.indexOf(blockVariation.name)) {
//                 console.log('not allowed,' + blockVariation.name + '!');
//                 wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
//             }
//         });
        
//     };
// });

