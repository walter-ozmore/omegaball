<!DOCTYPE html>
<html>
  <head>
    <title>Test</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

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

      function runGame(continuous = false) {
        let args = '{"teams":["HEAV","STYX"],"rules":{"displayPickupMessages":1,"useOutPoints":1,"defaultOutPointsAmount":"2","speed":"80"}}';

        ajax("/omegaball/ajax/run-simulation.php",
          function() {
            if (this.readyState != 4 || this.status != 200) return;
            let txt = this.responseText;
            let obj = null;
            try {
              obj = JSON.parse(txt);
              console.log(obj);
            } catch {
              console.error(txt);
              return;
            }

            // Display stats
            let stats = {
              "Number of Messages": obj.game.length,
              "Run Time": obj.game.length * 8,
              "Total Length": txt.length,
              "Average Length per Message": Math.floor( txt.length / obj.game.length )
            };
            // drawStats( stats, document.getElementById("stats") );
            allStats.push(stats);
            average();

            if( continuous )
              runGame( continuous );
          }, "q="+args
        );
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
    <div class="border" style="width: 30em; padding: .5em;">
      <h2 style="width: auto; padding: 0em; margin: 0em;">Average Game Stats</h2>
      <p id="numberOfGames"></p>
      <p id="avg-stats"></p>
      <button onclick="runGame(true)">Run Indefinitely</button>
      <button onclick="runGames(100)">Run 100 Game</button>
      <button onclick="clearStats()">Clear</button>
    </div>
  </body>
</html>