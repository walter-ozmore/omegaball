/**
 * Appends a new link to the navagation with the given information
 *
 * @param name The name that should be displayed
 * @param url The location that the link should lead
 */
function addNavLink(name, url) {
  let link = document.createElement("a");
  link.innerHTML = name;
  link.href = url;

  let links = document.getElementById("links");
  links.appendChild(link);
}


/**
 * Searches the URL of the page and compares it to the links in the links
 * section if the link and URL matches then it will append the class 'selected'
 * to it.
 */
function checkSelected() {
  let links = document.getElementById("links");
  if(links == undefined) return;
  let url = window.location.href.split('?')[0];

  var children = links.children;
  for (var i = 0; i < children.length; i++) {
    var child = children[i];
    if(url.toUpperCase().endsWith( "/" + child.innerHTML.toUpperCase() )) {
      child.classList.add("selected");
    }
  }
}



/**
 * @brief Makes a color darker or brighter by the specified amount.
 *
 * This function takes a hex color code and a numeric amount between 0 and 1,
 * and returns a new hex color code that is either darker or brighter than the
 * original, depending on whether the amount is positive or negative. If the
 * amount is positive, the color is made brighter; if the amount is negative,
 * the color is made darker.
 *
 * @param hexColor The hex color code to modify.
 * @param amount The amount by which to make the color brighter or darker.
 * Positive values make the color brighter; negative values make it darker.
 * Values outside the range of 0 to 1 will be clamped to that range.
 * @return The modified hex color code.
 */
function modifyColorBrightness(hexColor, amount) {
  // Convert hex color to RGB format
  const hex = hexColor.replace('#', '');
  const r = parseInt(hex.substring(0, 2), 16);
  const g = parseInt(hex.substring(2, 4), 16);
  const b = parseInt(hex.substring(4, 6), 16);

  // Calculate modified RGB values
  let modifiedR = Math.round( r * amount );
  let modifiedG = Math.round( g * amount );
  let modifiedB = Math.round( b * amount );

  // Fit with in 0-255
  if( modifiedR < 0 ) modifiedR = 0;
  if( modifiedG < 0 ) modifiedG = 0;
  if( modifiedB < 0 ) modifiedB = 0;

  if( modifiedR > 255 ) modifiedR = 255;
  if( modifiedG > 255 ) modifiedG = 255;
  if( modifiedB > 255 ) modifiedB = 255;

  // Convert to hex
  let hexR = modifiedR.toString(16);
  let hexG = modifiedG.toString(16);
  let hexB = modifiedB.toString(16);

  // Fit within size
  if( hexR.length == 1 ) hexR = "0" + hexR;
  if( hexG.length == 1 ) hexG = "0" + hexG;
  if( hexB.length == 1 ) hexB = "0" + hexB;

  // Convert modified RGB values back to hex format
  // const modifiedHex = `#${(modifiedR < 16 ? '0' : '') + modifiedR.toString(16)}${(modifiedG < 16 ? '0' : '') + modifiedG.toString(16)}${(modifiedB < 16 ? '0' : '') + modifiedB.toString(16)}`;
  const modifiedHex = "#"+hexR+hexG+hexB;

  return modifiedHex;
}


/**
 * @brief Send an AJAX request to a server.
 *
 * This function sends an AJAX request to the specified URL using the POST
 * method, and executes the specified function when the response is received.
 * The optional arguments can be used to send data along with the request.
 *
 * @param {string} url The URL to send the request to.
 * @param {function} fun The function to execute when the response is received.
 * @param {string} args Optional arguments to send with the request.
 */
function ajax(url, fun, args="") {
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = fun;

  xhttp.open("POST", url, true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send(args);
}


function syncAjax(url, args="") {
  try {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(args);

    if (xhr.status === 200) {
      return xhr.responseText;
    }
    return null;
  } catch (error) {
    return null;
  }
}


/**
 * A quick way to create an element with innerHTML set
 *
 * @param {string} type
 * @param {string} innerHTML
 * @returns The created element
 */
function mkEle(type, innerHTML) {
  let ele = document.createElement(type);
  ele.innerHTML = innerHTML;
  return ele;
}


/**
 * Toggle the highlight class on a selected element and remove it from all other
 * elements.
 *
 * @param {HTMLElement} selectElement - The element to add the highlight class to.
 * @param {HTMLElement} checkElement - The element to remove the highlight class
 * from and all its child elements.
 */
function toggleHighlight(selectElement, checkElement=null) {
  if( checkElement != null ) {
    unHighlight(checkElement, true)
  }
  // selectedElement.classList != undefined && selectedElement.classList.contains("selected")

  // Add the highlight class to the selected element
  if( selectElement.classList != undefined && selectElement.classList.contains("selected")) {
    unHighlight(selectElement);
    return;
  }
  highlight(selectElement);
}

function unHighlight(element, recursive = false) {
  if( recursive == true ) {
    const elementsToUnhighlight = element.querySelectorAll('*');

    for(let index in elementsToUnhighlight) {
      let element = elementsToUnhighlight[index];
      unHighlight(element);
    }
  }

  if( element.classList == undefined) return;

  if( element.classList.contains("selected") ) {
    element.classList.remove("selected");
    element.style.color = element.style.backgroundColor;
    element.style.backgroundColor = "unset";
  }
}

function highlight(element) {
  if( element.classList != undefined && element.classList.contains("selected"))
    return;
  element.classList.add('selected');

  element.style.backgroundColor = element.style.color;
  element.style.color = "black";
}

function mergeArgs(defaultArgs, args) {
  // Make a copy of the default arguments object
  const mergedArgs = { ...defaultArgs };

  // Merge the arguments from the second object
  for (const key in args) {
    if (args.hasOwnProperty(key)) {
      mergedArgs[key] = args[key];
    }
  }

  return mergedArgs;
}

function getSelectedElements(element) {
  var selectedElements = [];
  var childNodes = element.childNodes;

  for (var i = 0; i < childNodes.length; i++) {
    var childNode = childNodes[i];

    if (childNode.nodeType === Node.ELEMENT_NODE) {
      var classList = childNode.classList;

      if (classList.contains('selected')) {
        selectedElements.push(childNode.innerHTML);
      }

      if (childNode.hasChildNodes()) {
        let sub = getSelectedElements(childNode);
        selectedElements = selectedElements.concat(sub);
      }
    }
  }

  return selectedElements;
}

function getDivisionElement(args) {
  // Check arguments
  defaultArgs = {
    "multiSelect": false,
    "noColumns": false,
    "onClickTeam": undefined
  };
  args = mergeArgs(defaultArgs, args);

  // Check if data is loaded
  if(data == undefined) {
    console.warn("Warning: getDivisionElement was called but data is undefined. Fetching data synchronously. This should be avoided.");
    syncFetchData();
    return;
  }

  // Create the grid element
  let gridDiv = document.createElement("div");
  gridDiv.classList.add("league-grid");
  if( args["noColumns"] == true )
    gridDiv.style.gridTemplateColumns = "1fr";

  let divElements = [];

  for(let acronym in data["teams"]) {
    let team = data["teams"][acronym];
    let division = team.division;
    let ele = divElements[division];

    if( ele == undefined ) {
      ele = document.createElement("div");
      ele.appendChild( mkEle("h3", team["division"]) );
      gridDiv.appendChild( ele );
      divElements[division] = ele;
    }

    let teamNameEle = document.createElement("p");
    teamNameEle.classList.add("prevent-select");
    teamNameEle.style.color = team["teamColor"] ;
    teamNameEle.style.cursor = "pointer";
    teamNameEle.innerHTML = team["teamName"];
    teamNameEle.onclick = function() {
      if(args["multiSelect"]) {
        toggleHighlight(teamNameEle);
      } else {
        toggleHighlight( teamNameEle, gridDiv );
      }

      if(typeof args["onClickTeam"] === "function") {
        onClickTeam = args["onClickTeam"];
        onClickTeam({
          "element": this,
          "teamIndex": team["acronym"],
          "selected": (teamNameEle.classList != undefined && teamNameEle.classList.contains("selected"))
        });
      }
    };
    ele.appendChild( teamNameEle );
  }

  return gridDiv;
}


function fetchData(func, async=true) {
  if(!async) {
    let text = syncAjax("/omegaball/ajax/get-data.php");
    data = JSON.parse( text );
    return;
  }

  ajax("/omegaball/ajax/get-data.php", function() {
    if (this.readyState != 4 || this.status != 200) return;
    data = JSON.parse(this.responseText);
    func();
  });
}

function syncFetchData() {
  let text = syncAjax("/omegaball/ajax/get-data.php");
  data = JSON.parse( text );
}

function colorTeamName(args={}) {
  defaultArgs = {
    "team": null, // Required
    "message": null,
    "type": 0// 0:Full Team Name  1:Acronym
  };
  args = mergeArgs(defaultArgs, args);

  if(team == null) return "NULL";

  // Grab color
  let color = "rgb(255, 102, 255)"


}

function getTeamColor(teamAcronym) {

}


/**
 * Runs the given function when the window loads, if the
 * window is already loaded it will just run the function
 *
 * @param {function} func
 */
function onWindowLoad(func) {
  if(windowLoaded == false) {
    windowLoadedFunctions.push(func);
    return;
  }
  func();
}



// Global
var data;
var windowLoadedFunctions = [ checkSelected ];
var windowLoaded = false;

// Run all window load functions
window.onload = function() {
  windowLoaded = true;
  for(let i=0; i<windowLoadedFunctions.length; i++) {
    windowLoadedFunctions[i]();
  }
};