<!DOCTYPE html>
<html>
  <head>
    <title>Live</title>
    <?php
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php";
    ?>

    <style>
      .teams {
        display: grid;
        grid-template-columns: 1fr 1fr;
        width: 100%;
      }

      .team {
        margin: 0 1em 0 1em;
      }

      .output {
        padding: 4em;
        max-height: 40em;
        overflow: auto;
      }

      .roundSummary {
        border: solid;
        border-color: gray;
        margin: .5em;
        padding: .5em;
      }

      /* Drop down menu stuff */
      /* The container <div> - needed to position the dropdown content */
      .dropdown {
        display: inline-block;
      }

      /* Dropdown Content (Hidden by Default) */
      .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
      }

      /* Links inside the dropdown */
      .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
      }

      /* Change color of dropdown links on hover */
      .dropdown-content a:hover {background-color: #ddd;}

      /* Show the dropdown menu on hover */
      .dropdown:hover .dropdown-content {display: block;}

      /* Change the background color of the dropdown button when the dropdown content is shown */
      .dropdown:hover {background-color: rgba(255, 255, 255, .1);}
    </style>

    <script>
      function run(type="normal") {
        document.getElementById("controller").style.display = "none";

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            let txt = this.responseText;
            console.log(txt);
            var obj = JSON.parse(txt);
            // console.log(obj);

            let output = document.getElementById("output");

            // Clear info
            output.innerHTML = "";
            document.getElementById("teams").innerHTML = "";

            // Display teams
            let eo = 0;
            for(let key in obj.teams) {
              displayTeam( obj.teams[key], ((eo++%2==0)?"left":"right") );
            }

            // Display stats
            let statsDiv = document.getElementById("stats");
            let SECONDS = obj.game.length * 8;
            statsDiv.innerHTML = `
            Number of Messages `+obj.game.length +`<br>
            Time to complete game: `+new Date(SECONDS * 1000).toISOString().slice(11, 19);


            let delayTime = document.getElementById("speed").value;
            for(let x=0;x<obj.game.length;x++) {
              setTimeout(
                function () {
                  processTimeSlice( obj.game[x], (x == obj.game.length - 1) )
                }, delayTime * x);
            }
          }
        };

        let json = {};
        json.teams = [
          document.getElementById("team1").value,
          document.getElementById("team2").value
        ];
        let team1 = document.getElementById("team1").value;
        let team2 = document.getElementById("team2").value;
        if(type == "normal")
          xhttp.open("GET", "/omegaball/simulation/simulate-game.php?q="+JSON.stringify(json)+"&team1="+team1+"&team2="+team2, true);
        else
          xhttp.open("GET", "/omegaball/simulation/op/simulate-game.php?q="+JSON.stringify(json)+"&team1="+team1+"&team2="+team2, true);
        xhttp.send();
      }

      function processTimeSlice(timeSlice, end = false) {
        let output = document.getElementById("output");

        let message = document.createElement("p");
        message.innerHTML = timeSlice.message;

        output.append(message);

        window.scrollTo(0, document.body.scrollHeight);
        output.scrollTop = output.scrollHeight;

        if( Object.hasOwn(timeSlice, "teams") ) {
          document.getElementById("teams").innerHTML = "";

          let eo = 0;
          let teams = timeSlice.teams;
          for(let key in teams) {
            displayTeam( teams[key], ((eo++%2==1)?"left":"right") );
          }
        }

        // Show the control buttons again
        if(end) {
          document.getElementById("controller").style.display = "block";
        }
      }

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

          // player.classList.add("indent");
          player.innerHTML += (textAlign=="right")? extra + playerData.playerName: playerData.playerName + extra;
          team.appendChild( player );
        }

        teamsDiv.appendChild( team );
      }

      function onClickTeam(args) {
        if(args["selected"]) {
          selectedTeams.push( args["teamIndex"] );
        } else {

        }
      }

      var selectedTeams = [];

      document.addEventListener("DOMContentLoaded", function() {
        ajax("/omegaball/ajax/get-data.php", function() {
          if (this.readyState != 4 || this.status != 200) return;
          data = JSON.parse(this.responseText);

          let divisionElement = getDivisionElement({"onClickTeam":onClickTeam, "multiSelect": true});

          document.getElementById("teamSelector").appendChild(divisionElement);
        });
      });
    </script>

    <?php
      function createSelector($id) {
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

        echo "<select id='$id'>";
        $conn = connectDB("newOmegaball");
        $query = "SELECT teamName FROM Team WHERE league='The Alphaleague'";
        $result = runQuery($conn, $query);
        while ($row = $result->fetch_assoc()) {
          $teamName = $row["teamName"];
          echo "<option value='$teamName'>$teamName</option>";
        }
        echo "</select>";
      }
    ?>
  </head>

  <header>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php";
    ?>
  </header>

  <body>
    <div id="controller">
      <div id="teamSelector"></div>
      <button onclick="run()">Start Match</button><br>

      <select id="speed">
        <option value="0">Instant</option>
        <option value="80">100x</option>
        <option value="800">10x</option>
        <option value="8000">1x</option>
      </select>
    </div>


    <div id="teams" class="teams"></div>
    <div id="stats"></div>
    <div class="output" id="output"></div>
  </body>
</html>