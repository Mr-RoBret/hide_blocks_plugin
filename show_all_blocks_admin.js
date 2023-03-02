wp.domReady(function () {

    // display all blocks code
    const displayBlocksAsChecklist = () => {
        wp.blocks.getBlockTypes().forEach( (blockType)=> {
            console.log(blockType.name);
        });
    }
 
    displayBlocksAsChecklist();
    
});