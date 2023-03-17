// get various parts of settings-page table (automatically created) and apply id's for styling

jQuery(document).ready(function ($) {
    
    $("table > tbody > tr > td").addClass("listAndField");

    //const listField = document.createElement("div");
    const listFieldContent = document.getElementsByClassName('listAndDiv');
    const listAndField = document.getElementsByClassName("listAndField");
    listAndField.appendChild(listFieldContent);

    // $(".listAndField").append();

});