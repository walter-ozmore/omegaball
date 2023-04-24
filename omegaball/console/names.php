<!DOCTYPE html>
<html>
  <head>
    <title>League</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

    <style>
      .table {
        width: auto;
        height: 500px;
        margin: 2em auto auto auto;
        overflow-y: scroll;
        border: 3px solid;
      }

      .name, th, td {
        border: 1px solid;
        width: 100%;
        border-collapse: collapse;
      }

      .name th {
        width: 33%;
      }
    </style>
    <script>
      ajaxJson("/omegaball/ajax/get-names.php", function(obj){
        // Grab the table
        var table = document.getElementById("names");
        // Loop through the data
        for (let x of obj) {
          // Assign the values from the row to the variables
          let playerName = x["playerName"];
          let inGame = x["inGame"];
          let pos = x["position"];

          // Insert the row and cells
          var row = table.insertRow(1);
          var playerNameCell = row.insertCell(0);
          var inGameCell = row.insertCell(1);
          var posCell = row.insertCell(2);

          // Modify the cell's HTML
          playerNameCell.innerHTML = playerName;
          inGameCell.innerHTML = inGame;
          posCell.innerHTML = pos;
        }
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <div class=table>
    <table id="names" class=name>
      <tr>
        <th>Name</th>
        <th>inGame</th>
        <th>Position</th>
      </tr>
    </table>
  </body>
</html>