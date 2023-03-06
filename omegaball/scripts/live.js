function run() {
  console.log(selectedTeams);

  let args = {};
  args["teams"] = selectedTeams;
  args["rules"] = {};

  for(let x in optionElements) {
    let id = optionElements[x];
    let element = document.getElementById( id );
    switch(element.value) {
      case "true":
        break;
    }
    if(element.value == "true") {
      args["rules"][element.id] = 1;
    } else if(element.value == "false") {
      args["rules"][element.id] = 0;
    } else {
      args["rules"][element.id] = element.value;
    }
  }

  console.log(args);

  // Hide controls so they can't spam it
  document.getElementById("controller").style.display = "none";

  ajax(
    "/omegaball/ajax/run-simulation.php",
    function() {
      if (this.readyState != 4 || this.status != 200) return;
      let obj = null;
      try {
        obj = JSON.parse(this.responseText);
        console.log(obj);
      } catch {
        console.error(this.responseText);
        return;
      }

      let output = document.getElementById("output");

      // Clear info
      output.innerHTML = "";

      // Display teams
      displayTeams(obj.teams);

      // Display stats
      let statsDiv = document.getElementById("stats");
      let SECONDS = obj.game.length * 8;
      statsDiv.innerHTML = `
      Number of Messages `+obj.game.length +`<br>
      Time to complete game: `+new Date(SECONDS * 1000).toISOString().slice(11, 19);


      let delayTime = document.getElementById("speed").value;
      for(let x=0;x<obj.game.length;x++) {
        if(delayTime > 10)
          setTimeout(
            function () {
              processTimeSlice( obj.game[x], (x == obj.game.length - 1) )
            },
            delayTime * x
          );
        else
          processTimeSlice( obj.game[x], (x == obj.game.length - 1) )
      }
    },
    "q="+JSON.stringify(args)
  );
}

function processTimeSlice(timeSlice, end = false) {
  let output = document.getElementById("output");

  let message = document.createElement("p");
  message.innerHTML = timeSlice.message;

  output.append(message);

  // window.scrollTo(0, document.body.scrollHeight);
  output.scrollTop = output.scrollHeight;

  if( Object.hasOwn(timeSlice, "teams") ) {
    document.getElementById("teams").innerHTML = "";
    displayTeams(timeSlice.teams);
  }

  // Show the control buttons again
  if(end) {
    document.getElementById("controller").style.display = "block";
  }
}

/**
 * Updates the teams div using the information in teams
 *
 * @param {array} teams
 */
function displayTeams(teams) {
  document.getElementById("teams").innerHTML = "";

  let eo = 0;
  for(let key in teams) {
    displayTeam( teams[key], ((eo++%2==1)?"left":"right") );
  }
}


/**
 * Updates the given team on the team div
 *
 * @param {array} teamData
 * @param {string} textAlign
 */
function displayTeam(teamData, textAlign="center") {
  let teamsDiv = document.getElementById("teams");

  let team = document.createElement("div");
  team.style.color = teamData.teamColor;
  team.style.textAlign = textAlign;
  team.classList.add("team");

  let teamName = document.createElement("h2");
  teamName.innerHTML += teamData.teamName;
  if(textAlign == "left" || textAlign == "center") {
    teamName.style.marginRight = "auto";
  }
  if(textAlign == "right" || textAlign == "center") {
    teamName.style.marginLeft = "auto";
  }
  team.appendChild( teamName );

  // for(let x=0;x<teamData.players.length;x++) {
  for(let key in teamData.players) {
    let playerData = teamData.players[key];

    let player = document.createElement("p");
    if( playerData.inGame == false ) {
      let dim = modifyColorBrightness( teamData.teamColor, 0.65 );
      player.style.color = dim;
      player.style.textDecoration = 'line-through';
    }

    let extra = "";
    for(let x=0;x<playerData.heldBalls;x++) {
      extra += "*";
    }
    for(let x=0;x<playerData.outPoints;x++) {
      extra += "x";
    }

    // player.classList.add("indent");
    player.innerHTML += (textAlign=="right")? extra + playerData.playerName: playerData.playerName + extra;
    team.appendChild( player );
  }

  teamsDiv.appendChild( team );
}
var selectedTeams = [];
var optionElements = [];

document.addEventListener("DOMContentLoaded", function() {
  ajax("/omegaball/ajax/get-data.php", function() {
    if (this.readyState != 4 || this.status != 200) return;
    data = JSON.parse(this.responseText);

    let divisionElement = getDivisionElement({
      "onClickTeam":function(args) {
        if(args["selected"]) {
          selectedTeams.push( args["teamIndex"] );
        } else {
          // Delete from array
          selectedTeams.splice( selectedTeams.indexOf(args["teamIndex"]), 1 );
        }
      },
      "multiSelect": true
    });

    // divisionElement.style.width = "100%";
    divisionElement.classList.add("centered");

    document.getElementById("teamSelector").appendChild(divisionElement);

    createOption("displayPickupMessages", "Display Ball Pickup Messages");
    createOption("useOutPoints", "Use out points");
    createOption("defaultOutPointsAmount", "Default out points amount", {"2":"2", "3":"3"});
    createOption("speed", "Display speed", {"80":"Fastest", "800":"Faster", "8000":"Normal"});
  });
});