<!DOCTYPE html>
<html>
  <head>
    <title>Account</title>
    <?php
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php";
    ?>

    <style>
      .linksVertical a{
        display: block;
      }

      .league-grid {
        display: grid;
        grid-template-rows: 1fr 1fr;
        row-gap: 2em;
      }

      .league-grid h3, .league-grid p {
        margin: 0px;
        padding: 0px;
        text-align: left;
      }

      @media screen and (min-width: 34em) {
        .league-grid {
          grid-template-columns: 1fr 1fr;
          column-gap: 1em;
          width: 35em;
        }
      }
    </style>

    <script src="https://www.everyoneandeverything.org/account/lib.js"></script>
    <script>
      function login() {
        // let uname = document.getElementById("uname").value;
        let div = document.getElementById("login");
        let uname = div.querySelector("[name='uname']").value;
        let pword = div.querySelector("[name='pword']").value;

        sendLoginRequest(uname, pword, false, function(obj){
          ajax("/omegaball/ajax/fetch-user", function() {
            if (this.readyState != 4 || this.status != 200) return;
            let data = JSON.parse(this.responseText);
            var user = data;
            user["username"] = obj["username"];
            console.log(user);

          }, "uid="+obj["uid"]);
        });
      }

      function createGrid() {
        let notEle = document.createElement("div");
        notEle.classList.add("notification");
        notEle.style.width = "unset";
        notEle.style.maxWidth = "unset";

        let message = mkEle("h2", "Select a team to support");
        message.style.marginLeft = "auto";
        message.style.marginRight = "auto";
        notEle.appendChild( message );

        divisionElement = getDivisionElement();
        divisionElement.style.marginBottom = "2em";
        notEle.appendChild(divisionElement);

        let confirmButton = document.createElement("button");
        confirmButton.innerHTML = "Confirm Selection";
        confirmButton.onclick = function() {
          closeNotification( this );
        };
        notEle.appendChild(confirmButton);

        document.body.appendChild( notEle );
      }

      function selectTeam(ele, divisionElement, teamIndex) {
        // Select one and unselect all of the others
        toggleHighlight( ele, divisionElement );
      }

      var data;
      ajax("/omegaball/ajax/get-data.php", function() {
        if (this.readyState != 4 || this.status != 200) return;
        data = JSON.parse(this.responseText);
        console.log(data);
        createGrid();
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>

  <body>
    <div id="login" class="border">
      <p class="error"></p>

      <label>Username:</label>
      <input type="text" name="uname"><br><br>

      <label>Password:</label>
      <input type="password" name="pword"><br><br>

      <button onclick="login()">login</button>
    </div>

    <!-- <div class="notification">
      <h2>Select a team to support this season</h2>
      <div class="league-grid">
        <div>
          <h3>CHAOTIC EVIL</h3>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
        </div>
        <div>
          <h3>CHAOTIC EVIL</h3>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
        </div>
        <div>
          <h3>CHAOTIC EVIL</h3>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
        </div>
        <div>
          <h3>CHAOTIC EVIL</h3>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
          <p>THE AVALON TEMPLARS</p>
        </div>
      </div>
    </div> -->
  </body>
</html>