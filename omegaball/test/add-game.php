<!DOCTYPE html>
<html>
  <head>
    <title>Test</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

    <style>
      h2 {
        width: auto; padding: 0em; margin: 0em;
      }

      .border {
        /* width: 30em; */
        padding: .5em;
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
    </style>

    <script src="/omegaball/scripts/live.js"></script>
    <script>
      var allStats = [];

      function drawStats(stats, ele) {
        let innerHTML = "";

        for(let key in stats) {
          let value = Math.floor( stats[key] );

          switch(key) {
            case "Run Time":
              value = new Date(value * 1000).toISOString().slice(11, 19);
              break;
            case "Average Length per Message":
            case "Total Length":
              value = value + " characters";
              break;
          }

          innerHTML += key+": "+value+"<br>";
        }

        ele.innerHTML = innerHTML;
      }

      function average() {
        let average = {...allStats[0]};
        // console.log(allStats[0]);
        for(let stats of allStats) {
          for(let key in average) {
            average[key] += stats[key];
          }
        }
        for(let key in average) {
          average[key] = average[key] / (allStats.length);
        }
        drawStats(average, document.getElementById("avg-stats"));
        document.getElementById("numberOfGames").innerHTML = "Number of games: "+allStats.length;
      }

      function runGame() {
        let args = '{"teams":["HEAV","STYX"],"rules":{"displayPickupMessages":1,"useOutPoints":1,"defaultOutPointsAmount":"2","speed":"80"}}';

        ajaxJson("/omegaball/ajax/run-simulation.php", function(obj) {
          // Display stats
          let stats = {
            "Number of Messages": obj.game.length,
            "Run Time": obj.game.length * 8,
            "Total Length": txt.length,
            "Average Length per Message": Math.floor( txt.length / obj.game.length )
          };
          drawStats( stats, document.getElementById("stats") );
          allStats.push(stats);
          average();

          decode(obj);
        }, "q="+args );
      }

      function runGames(number) {
        for(let x=0;x<number;x++) {
          runGame();
        }
      }

      function clearStats() {
        allStats = [];
        document.getElementById("avg-stats").innerHTML = "";
        document.getElementById("numberOfGames").innerHTML = "Number of games: " + allStats.length;
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <div class="border" id="game-window">
      <h2>Average Game Stats</h2>
      <p id="numberOfGames"></p>
      <p id="avg-stats"></p>
      <button onclick="runGame()">Run</button>
      <button onclick="runGames(100)">Run 100 Game</button>
      <button onclick="clearStats()">Clear</button>
    </div>
    <div class="border">
      <h2>Last Game</h2>
      <p id="stats"></p>
      <h2>Output</h2>
      <div id="output"></div>
    </div>
  </body>
</html>