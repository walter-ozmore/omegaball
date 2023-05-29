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
      var offset = 0;
      var pageSize = 20;

      ajaxJson("/omegaball/ajax/get-names.php", function(obj){
        // Grab the table
        var table = document.getElementById("names");
        // Loop through the data
        for (let x of obj) {
          let eleChk = mkEle("input");
          eleChk.setAttribute("type", "checkbox");
          if (x["inGame"] == 1){
            eleChk.checked = true;
          }else{
            eleChk.checked = false;
          }

          // -1, 1, 3
          let elePos = mkEle("select");
          let optionOne = mkEle("option");
          let optionTwo = mkEle("option");
          let optionThree = mkEle("option");

          optionOne.innerHTML = "Any Name";
          optionTwo.innerHTML = "First Name";
          optionThree.innerHTML = "Last Name";

          if (x["position"] == -1) {
            optionOne.selected = true;
          }
          if (x["position"] == 1) {
            optionTwo.selected = true;
          }
          if (x["position"] ==3) {
            optionThree.selected = true;
          }

          elePos.appendChild(optionOne);
          elePos.appendChild(optionTwo);
          elePos.appendChild(optionThree);

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
          inGameCell.appendChild(eleChk);
          posCell.appendChild(elePos);
        }
      }, {offset: 0});

      // this function will take the new offset and 
      // update the name table with the new offset
      function updateTable(){

      }

      // offset subtracted
      function goBack(){
        if (offset < pageSize){
          return;
        }
        offset -= pageSize;

        updateTable();
      }

      // offset added
      function goForward(){
        offset += pageSize;

         updateTable();
      }

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
    </div>
    <button onclick = "goBack()"> Back </button>
    <button onclick = "goForward()"> Forward </button>
  </body>
</html>