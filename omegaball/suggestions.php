<!DOCTYPE html>
<html>
  <head>
    <title>Suggestions</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php";?>
    <script>
      /**
       * @brief Generates a Name.
       *
       * Generates a first and last name using the information
       * from the Names SQL Database
       *
       */
      function generateNames() {
        ajax("/omegaball/ajax/name-gen.php", function() {
          if(this.readyState != 4 || this.status != 200) return;
          let text = this.responseText;
          let obj = JSON.parse(text);
          let gen = document.getElementById("generate");
          gen.innerHTML = "";

          for(let name of obj) {
            gen.appendChild(mkEle("p", name));
          }
        });
      }


      /**
       * @brief Add a name to the SQL Database
       *
       * Allows the users to submit name suggestions to the SQL
       * Database. Checks if the name is a first name, last name,
       * both, or neither. If neither, show an error.
       *
       */
      function submitName() {
        let first = document.getElementById("first");
        let last = document.getElementById("last");
        let pos = -1;
        let name = document.getElementById("name").value;

        if (first.checked && last.checked) {
          pos = -1;
        } else if(last.checked) {
          pos = 3;
        } else if(first.checked) {
          pos = 1;
        } else {
          let error = document.getElementById("error");
          error.innerHTML = "PLEASE SELECT FIRST, LAST, OR BOTH.";
          error.style.display = "block";
        }

        let args ="pos="+pos+"&name="+name;

        ajax("/omegaball/ajax/add-name.php", function() {
          if(this.readyState != 4 || this.status != 200) return;
          let text = this.responseText;
        }, args);
      }
    </script>
    <style>
      .box {
        width: 25em; /* set your desired width */
        margin: 1em auto; /* set margin to auto */
        text-align: center;
        align-items: center;
        justify-content: center;
        padding: 1.5em;
      }

      .words {
        margin: 0em 0em 1em 0em;
      }
    </style>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <div class="box border">
      <p class="words">
        Please input a name and designate first or last. If both, select both boxes.
      </p>

      <p>
        <label for="name">NAME:</label>
        <input type="text" id="name" name="name">
      </p>

      <p style="margin: 1em">
        <input type="checkbox" name="first" id="first">
        <label for="first">FIRST NAME</label>
        <br>
        <input type="checkbox" name="last" id="last">
        <label for="last">LAST NAME</label>
      </p>

      <p id="error" class="error" style="display: none;"></p>
      <button onclick="submitName()">SUBMIT NAME</button>
    </div>


    <div class="box border">
      <div id="generate"></div>
      <button onclick="generateNames()">GENERATE NAMES</button>
    </div>
  </body>
</html>