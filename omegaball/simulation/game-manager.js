class GameManager {
  addWindow(element) {

    this.teamDisplay = mkEle("div");
    this.teamDisplay.classList.add("game-window-team-display");
    element.appendChild(this.teamDisplay);

    this.textDiv = mkEle("div");
    this.textDiv.classList.add("game-text-viewer");
    element.appendChild(this.textDiv);
  }

  runGame(args = {}, returnFunction = null, display = true) {
    args = {
      teams: ["STYX", "HEAV"]
    };

    ajaxJson( "/omegaball/simulation/ajax.php", function(obj) {
      if( returnFunction !== null ) returnFunction(obj);
      if(!display) return;

      // Clear info
      gameManager.textDiv.innerHTML = "";

      for(let x=0;x<obj.game.length;x++) {
        let timeSlice = obj.game[x];

        gameManager.processTimeSlice(timeSlice, gameManager);
      }

      console.log("job done");
    }, args );
  }


  /**
   * Processes the slice of time, or the message
   *
   * @param {*} timeSlice
   * @param {*} end Indicates if this time is the last message
   */
  processTimeSlice(timeSlice, end = false) {
    let message = document.createElement("p");
    message.innerHTML = timeSlice.message;

    gameManager.textDiv.appendChild(message);

    // Scroll to the bottom of the text input
    // window.scrollTo(0, document.body.scrollHeight);
    gameManager.textDiv.scrollTop = gameManager.textDiv.scrollHeight;

    // Update teams if needed
    if( Object.hasOwn(timeSlice, "teams") ) {
      // document.getElementById("teams").innerHTML = "";
      // displayTeams(timeSlice.teams);
      gameManager.drawTeams(timeSlice.teams);
    }
  }


  /**
   * @brief Updates the teams dynamicly based on the information given. Ideally
   * the first time this runs all players and teams will be given then this can
   * be run with partial updates.
   *
   * Example first run gives all teams and players, second run only given one
   * player that has picked up a ball, only that players element will be changed
   * and updated.
   *
   * @param {json obj} data Data of the teams that will be updated
   */
  drawTeams(data) {
    if(gameManager.teams == undefined)
      gameManager.teams = {};
    let teams = gameManager.teams;

    for(let teamAcronym in data) {
      // Team object from the new data
      let freshTeam = data[teamAcronym];

      // If the team does not exists: create them
      if( typeof teams[teamAcronym] === "undefined" ) {
        // Create the new team and move data to them
        let team = {};
        team["teamName"] = freshTeam.teamName;
        team["teamColor"] = freshTeam.teamColor;
        team["players"] = {};

        // Draw team div
        let teamDiv = mkEle("div");
        teamDiv.style.color = team["teamColor"];
        teamDiv.appendChild(mkEle("h2", team["teamName"]));
        gameManager.teamDisplay.appendChild( teamDiv );

        // Append to list for later editing if needed
        team["div"] = teamDiv;

        teams[teamAcronym] = team;
      }

      // By here we know that a team does exists. Either we made it or it
      // already exists
      let team = teams[teamAcronym];

      // Check all players in the given team
      for(let playerName in data[teamAcronym]["players"]) {
        // Player object from the new data
        let freshPlayer = freshTeam["players"][playerName];

        if( typeof team["players"][playerName] === "undefined" ) {
          // Player not found, create one from the data
          let player = {};
          player["playerName"] = playerName;
          player["div"] = mkEle("p");

          // Push
          team["players"][playerName] = player;
          team.div.appendChild( player.div );
        }

        // By here we know that a player does exists. Either we made it or it
        // already exists
        let player = team["players"][playerName];
        player.div.innerHTML = player.playerName;
      }
    }

    gameManager.teams = teams; // Update team object with global
  }
}

let gameManager = new GameManager();