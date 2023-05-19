window.addEventListener('DOMContentLoaded', function () {

    // const table_all = document.getElementsByClassName("form-table");
    const table_bodies = document.querySelectorAll("tbody");

    // get HTMLcollection of both "th" and "tr" table elements
    const table_left_columns = document.querySelectorAll("th");
    const table_content_columns = document.querySelectorAll("td");
    // const table_content_columns = document.querySelectorAll("td");

    console.log(table_left_columns);
    
    // for each item in HTMLcollection of "th" elements, reduce width to 0
    for (let i = 0; i < table_left_columns.length; i++ ) {
        table_left_columns.item(i).style.width = "0";

    }

    table_content_columns.item(0).style.columnCount = 4;
    table_content_columns.item(1).style.columnCount = 2;

});

