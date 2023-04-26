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

      .game-selector {
        width: 10em;
      }
      .game-selector button {
        width: 100%;
        text-align: center;
        display: block;
        background-color: unset;
        color: white;
      }
      .game-selector button:hover {
        background-color: gray;
      }
      .wrapper {
        display: flex;
      }
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
      function addGames(obj) {
        let selector = document.getElementById("game-selector");
        selector.innerHTML = "";
        for(let entry of obj) {
          let ele = mkEle("button", entry["title"]);

          ele.onclick = function() {
            toggleHighlight(ele, selector);
            gameManager.load( entry["gameID"] );
          };

          selector.appendChild(ele);
        }
      }

      onWindowLoad(function() {
        gameManager.addWindow( document.getElementById("game-window") );
        gameManager.loadTitles(addGames);
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>

  <body>
    <div class="wrapper">
      <div id="game-selector" class="border game-selector"></div>
      <div id="game-window" class="border game-window"></div>
    </div>
  </body>
</html>