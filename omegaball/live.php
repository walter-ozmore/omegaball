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

    <!-- <script src="/omegaball/scripts/live.js"></script> -->
    <script src="/omegaball/simulation/game-manager.js"></script>
    <script>
      // function createOption(id, label, options={"true":"True", "false":"False"}) {
      //   // id = id.toLowerCase();

      //   let optionsStr = "";
      //   for (const [key, value] of Object.entries(options)) {
      //     optionsStr += `<option value="${key}">${value}</option>`;
      //   }

      //   let innerHTML = `
      //     <label>${label}</label>
      //     <select id='${id}'>
      //       ${optionsStr}
      //     </select><br>
      //   `;

      //   document.getElementById("settings").innerHTML += innerHTML;
      //   optionElements.push( id );
      // }

      function runGame() {
        gameManager.addWindow( document.getElementById("game-window") );
        gameManager.runGame({}, function(obj) {
          let txt = JSON.stringify(obj);
        });
      }

      function addGame(name) {
        let selector = document.getElementById("game-selector");
        let ele = mkEle("button", name);

        ele.onclick = function() {
          toggleHighlight(ele, selector);
        };

        selector.appendChild(ele);
      }

      onWindowLoad(function() {
        addGame("<span style='color: #00A4FF;'>AEON</span> vs. <span style='color: #8D2200;'>ARCH</span>");
        for(let x=1;x<=10;x++) {
          addGame("Game "+x);
        }
        runGame();
      });
    </script>
  </head>

  <header>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php";
    ?>
  </header>

  <body>
    <div class="wrapper">
      <div id="game-selector" class="border game-selector"></div>
      <div id="game-window" class="border game-window"></div>
    </div>
    <!-- <div id="controller">
      <div id="teamSelector"></div>
      <div id="settings"></div>

      <button onclick="run()">Start Match</button>
    </div>


    <div id="teams" class="teams"></div>
    <div id="stats"></div>
    <div class="output" id="output"></div> -->
  </body>
</html>