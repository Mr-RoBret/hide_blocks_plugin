# Block Hider Plugin

## A basic plugin for hiding blocks from the Block Selector in WordPress

**Author:** Bret Farley 

The purpose of this plugin is to allow a WordPress Administrator to create a list of blocks they wish to hide from the Block Selector menu. The list, entered into a settings field and separated by commas, will be converted into an array once submitted, and any name in the array that matches the list of blocks retrieved from the frontend (once all core blocks and plugin-based blocks can be found via the **Blocks API**) will be unregistered. 

Later, a separate settings field for Embed blocks will allow only certain Embed blocks to be registered and displayed in the Block Selector. 