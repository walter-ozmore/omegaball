<!DOCTYPE html>
<html>
  <head>
    <title>Live</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

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
      function buildController() {
        let divisionEle = getDivisionElement();

        let controllerEle = document.getElementById("controller");
        controllerEle.appendChild(divisionEle);

        let button = null;
        button = mkEle("button", "Simulate Game");
        controllerEle.appendChild(button);

        button = mkEle("button", "Save Game");
        controllerEle.appendChild(button);
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
    <div id="controller" class="box border">
      <button onclick="gameManager.generate();">Create New Game</button>
      <button onclick="gameManager.save();">Save Game</button>
    </div>
    <div id="game-window" class="border game-window"></div>
  </body>
</html>