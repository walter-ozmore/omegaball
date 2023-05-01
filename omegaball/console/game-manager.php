<!DOCTYPE html>
<html>
  <head>
    <title>Live</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

    <style>
      .box {
        width: 35em;
      }

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

      .game-window {
        width: 100%;
      }
      .game-window-team-display {
        display: grid;
        grid-template-columns: 1fr 1fr;
      }
    </style>

    <script src="/omegaball/simulation/game-manager.js"></script>
    <script>
      var selectedTeams = [];
      var saveButton = null;
      var simulateButton = null;

      function onTeamClick(args) {
        if(args.selected)
          selectedTeams.push(args.teamIndex);
        else {
          let index = selectedTeams.indexOf(args.teamIndex);
          if(index > -1) {
            selectedTeams.splice(index, 1);
          }
        }

        simulateButton.disabled = selectedTeams.length <= 1;
      }

      function buildController() {
        let divisionEle = getDivisionElement({
          multiSelect: true,
          onClickTeam: onTeamClick
        });

        let controllerEle = document.getElementById("controller");
        controllerEle.appendChild(divisionEle);

        simulateButton = mkEle("button", "Simulate Game");
        simulateButton.disabled = true;
        simulateButton.onclick = function() {
          gameManager.generate({
            teams: selectedTeams
          }, function() {
            saveButton.disabled = false;
          });
        };
        controllerEle.appendChild(simulateButton);

        // Create a new datetime input element
        let datetimeInput = document.createElement("input");
        datetimeInput.type = "datetime-local";
        datetimeInput.id = "datetime";
        datetimeInput.name = "datetime";

        // Set the default value of the datetime input element to the current date and time
        let now = new Date();
        let dateString = now.toISOString().slice(0, 16);
        datetimeInput.value = dateString;

        controllerEle.appendChild( datetimeInput );

        saveButton = mkEle("button", "Save Game");
        saveButton.disabled = true;
        saveButton.onclick = function() {
          gameManager.save();
        }
        controllerEle.appendChild(saveButton);
      }
      onWindowLoad(function() {
        gameManager.addWindow( document.getElementById("game-window") );
        fetchData(buildController);
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>

  <body>
    <div style="display: flex">
      <div class="border">
        <div id="game-stats"></div>
        <div id="controller"></div>
      </div>
      <div id="game-window" class="border game-window"></div>
    </div>
  </body>
</html>