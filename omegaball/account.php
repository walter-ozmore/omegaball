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
  </body>
</html>