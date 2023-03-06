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

    <script src="/omegaball/scripts/live.js"></script>
    <script>
      function createOption(id, label, options={"true":"True", "false":"False"}) {
        // id = id.toLowerCase();

        let optionsStr = "";
        for (const [key, value] of Object.entries(options)) {
          optionsStr += `<option value="${key}">${value}</option>`;
        }

        let innerHTML = `
          <label>${label}</label>
          <select id='${id}'>
            ${optionsStr}
          </select><br>
        `;

        document.getElementById("settings").innerHTML += innerHTML;
        optionElements.push( id );
      }
    </script>
  </head>

  <header>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php";
    ?>
  </header>

  <body>
    <div id="controller">
      <div id="teamSelector"></div>
      <div id="settings"></div>

      <button onclick="run()">Start Match</button>
    </div>


    <div id="teams" class="teams"></div>
    <div id="stats"></div>
    <div class="output" id="output"></div>
  </body>
</html>