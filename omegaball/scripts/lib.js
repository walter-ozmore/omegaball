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
 * @brief Creates a new notification element and appends it to the body of the
 * document.
 *
 * This function creates a new <div> element with the class "notification",
 * appends it to the body of the document, and then returns a reference to the
 * new element.
 *
 * @return A reference to the newly created notification element.
 */
function createNotification() {
  let notEle = document.createElement("div");
  notEle.classList.add("notification");
  document.body.appendChild( notEle );

  return notEle;
}


/**
 * Check if there is any notifications that should be displayed then displays
 * the relevant notifications
 */
function checkNotify() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState != 4 || this.status != 200) return;
    let obj;
    try {
      obj = JSON.parse( this.responseText );
    } catch(e) {
      console.log( this.responseText );
      return;
    }

    // Disabled because annoying
    // notifyChampionshipWin( obj.acronym, obj.teamName, "OKAY" );
  };
  xhttp.open("GET", "/omegaball/ajax/notify.php", true);
  xhttp.send();
}

function notifyChampionshipWin(acronym, name, buttonText) {
  let ele = createNotification();

  // Add image to the notification
  let img = document.createElement("img");
  img.src = "/omegaball/res/graphics/teams/"+ acronym.toLowerCase() +"/icon.png";
  img.onerror = function(){
    console.log("file with "+url+" invalid. Using default image.");
    img.src = "/omegaball/res/graphics/teams/unsorted/bodies-icon.png";
  }
  ele.appendChild( img );

  // Add top text
  let topText = document.createElement("p");
  topText.innerHTML = name.toUpperCase() +" HAVE WON THE SEASON 1 CHAMPIONSHIP!";
  ele.appendChild( topText );

  // Add bottom text
  let bottomText = document.createElement("p");
  bottomText.innerHTML = "THE PRIZE AUCTION WILL COMMENCE SHORTLY.";
  ele.appendChild( bottomText );

  // Add button
  let button = document.createElement("button");
  button.innerHTML = buttonText;
  button.onclick = function() {
    closeNotification(this);
  };
  ele.appendChild( button );
}


/**
 * @brief Deletes the first ancestor element with the "notification" class that
 * contains the given element.
 *
 * This function traverses up the document tree from the given element until it
 * finds an element with the "notification" class, and then deletes that
 * element. If no such element is found, this function does nothing.
 *
 * @param element The element to start the search from.
 */
function closeNotification(element) {
  // Go up untill you find an element with the class of notification
  let ancestor = element.parentNode;
  while (ancestor != null) {
    if (ancestor.classList.contains('notification')) {
      ancestor.remove();
      return;
    }
    ancestor = ancestor.parentNode;
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
    const elementsToUnhighlight = checkElement.querySelectorAll('*');

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



function getDivisionElement(args) {
  defaultArgs = {
    "multiSelect": false,
    "noColumns": false,
    "onClickTeam": undefined
  };
  args = mergeArgs(defaultArgs, args);

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
    teamNameEle.style.color = team["teamColor"] ;
    teamNameEle.innerHTML = team["teamName"];
    teamNameEle.style.cursor = "pointer";
    teamNameEle.onclick = function() {
      if(args["multiSelect"]) {
        toggleHighlight(teamNameEle);
      } else {
        toggleHighlight( teamNameEle, gridDiv );
      }

      if(typeof args["onClickTeam"] === "function") {
        onClickTeam = args["onClickTeam"];
        onClickTeam({
          "teamIndex": team["acronym"],
          "selected": (teamNameEle.classList != undefined && teamNameEle.classList.contains("selected"))
        });
      }
    };
    ele.appendChild( teamNameEle );
  }

  return gridDiv;
}


function fetchData() {
  ajax("/omegaball/ajax/get-data.php", function() {
    if (this.readyState != 4 || this.status != 200) return;
    var data = JSON.parse(this.responseText);
    console.log(data);
  });
}

window.onload = function() {
  checkNotify();
  checkSelected();
};