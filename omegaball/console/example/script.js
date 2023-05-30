// https://www.w3schools.com/jquery/jquery_ref_selectors.asp

/**
 * Creates a dropdown select element with specified options.
 *
 * @param {Array} options - An array of option objects to populate the dropdown.
 * @param {*} selected - The selected value (optional).
 * @return {jQuery} - The jQuery object representing the created dropdown select
 * element.
 */
function createSelect(options, selected = null) {
  let dropdown = $("<select>");

  // Create and append option elements to the dropdown
  options.forEach(function(option) {
    let optionElement = $("<option>").text(option.text).val(option.value);

    if(option.value == selected) {
      optionElement.attr("selected", true);
    }

    // Append to the dropdown
    dropdown.append(optionElement);
  });

  return dropdown;
}


/**
 * Fetches data using an AJAX call and updates the display area with the
 * retrieved data.
 */
function updateDisplay() {
  // Fetch data
  let args = {offset: offset};
  let returnFunction = function(obj) {
    console.log(obj);

    var table = $("#data");

    // Clear the display area
    table.find("tr:not(:first)").remove();

    // Create rows on the table
    for(let rowData of obj.data) {
      let row = $("<tr>");

      // Column 1: Name
      row.append(
        $("<td>").text( rowData.playerName )
      );

      // Column 2: In Game
      row.append( // Append to the row
        $("<td>").append( // Make data element in the row
          // Make the checkbox for the row
          $("<input>", {
            type: "checkbox",
            checked: rowData.inGame
          })
        )
      );

      // Column 3: Position
      let options = [
        { text: "Any Name"  , value:"-1" },
        { text: "First Name", value: "1" },
        { text: "Last Name" , value: "3" }
      ];
      let dropdown = createSelect(options, rowData.position);
      row.append( dropdown );

      // Append row to table
      table.append( row );
    }

  };

  // Run asynchronous ajax
  ajaxJson("ajax.php", returnFunction, args);
}


/**
 * Changes the global varable offset by the value given then updates the display
 *
 * @param delta The amount that the page will change
 */
function changePage(delta) {
  offset += delta;

  // Page is moving forward
  if( offset < pageRange[0] ) {
    offset = pageRange[0];
  }

  if( offset > pageRange[1] ) {
    offset -= delta;
  }

  updateDisplay();
}

// Global varables and runner code goes here
var offset = 0;
// Page limits, [0]: Min  [1]: Max
var pageRange = [0, 20];

// Wait until the page updates before changing the page
$(document).ready(function() {
  updateDisplay();
});