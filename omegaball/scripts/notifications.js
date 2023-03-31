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
 * Creates a pop up promping the user to select a team, the selection will then
 * be updated in SQL using ajax
 */
function showTeamSelectionNotification() {
  // If data is not found, fetch data
  if(data == undefined) {
    fetchData(showTeamSelectionNotification);
    return;
  }

  let selectedTeam;
  let notEle = document.createElement("div");
  notEle.classList.add("notification");
  notEle.style.width = "unset";
  notEle.style.maxWidth = "unset";

  let message = mkEle("h2", "Select a team to support");
  message.style.marginLeft = "auto";
  message.style.marginRight = "auto";
  notEle.appendChild( message );

  divisionElement = getDivisionElement({
    "onClickTeam": function(args) {
      selectedTeam = args["teamIndex"];
    }
  });
  divisionElement.style.marginBottom = "2em";
  notEle.appendChild(divisionElement);

  let confirmButton = document.createElement("button");
  confirmButton.innerHTML = "Confirm Selection";
  confirmButton.onclick = function() {
    closeNotification( this );
    Accounts.loadCurrentUser(function(user) {
      uid = user["uid"]
      ajax("/omegaball/ajax/update-user-team.php", function() {
        if (this.readyState != 4 || this.status != 200) return;
        // console.log(this.responseText);
      }, `uid=${uid}&team=${selectedTeam}`);
    });


  };
  notEle.appendChild(confirmButton);

  document.body.appendChild( notEle );
}

onWindowLoad(checkNotify);