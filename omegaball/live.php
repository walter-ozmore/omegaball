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
      function run() {
        document.getElementById("controller").style.display = "none";

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            let txt = this.responseText;
            // console.log(txt);
            var obj = JSON.parse(txt);
            // console.log(obj);

            let output = document.getElementById("output");

            // Clear info
            output.innerHTML = "";
            document.getElementById("teams").innerHTML = "";

            // Display teams
            for(let key in obj.teams) {
              displayTeam( obj.teams[key] );
            }

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
        xhttp.open("GET", "/omegaball/simulation/simulate-game.php?q="+JSON.stringify(json)+"&team1="+team1+"&team2="+team2, true);
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

          let teams = timeSlice.teams;
          for(let key in teams) {
            displayTeam( teams[key] );
          }
        }

        // Show the control buttons again
        if(end) {
          document.getElementById("controller").style.display = "block";
        }
      }

      function displayTeam(teamData) {
        let teamsDiv = document.getElementById("teams");

        let team = document.createElement("div");
        team.style.color = teamData.teamColor;

        let teamName = document.createElement("h2");
        teamName.innerHTML += teamData.teamName;
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

          player.classList.add("indent");
          player.innerHTML += playerData.playerName + extra;
          team.appendChild( player );
        }

        teamsDiv.appendChild( team );
      }
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
    <div id="teams" class="teams"></div>

    <center>
      <div id="controller">
        <?php createSelector("team1"); ?>
        <button onclick="run()" id="start-button">Start Match</button>
        <?php createSelector("team2"); ?>
        <select id="speed">
          <option value="10">Near Instant (1/100s)</option>
          <option value="100">Faster (1/10s)</option>
          <option value="1000">Fast (1s)</option>
          <option value="8000">Normal (8s)</option>
        </select>
      </div>
    </center>
    <div class="output" id="output"></div>
  </body>
</html>