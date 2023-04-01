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

      .border {
        width: auto;
        max-width: 20em;
        margin-top: .5em;
        margin-bottom: .5em;
        padding: .5em;
      }

      .error {
        color: red;
      }
    </style>

    <script src="/account/version-3/lib.js"></script>
    <script>
      function loginButton() {
        document.getElementById("login").style.display = "none";
        document.getElementById("sign-up").style.display = "none";
        account_login(function(data) {
          if(data["code"] == 0) {
            Accounts.loadCurrentUser();
          }
        });
      }

      function signupButton() {
        document.getElementById("login").style.display = "none";
        document.getElementById("sign-up").style.display = "none";
        account_signup(function(data) {
          if(data["code"] == 0) {
            Accounts.loadCurrentUser();
            checkNotify();
            return;
          }
        }, "omegaball");
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>

  <body>
    <div id="login" class="border centered">
      <p class="error" name="error"></p>

      <label>Username:</label>
      <input type="text" name="uname"><br><br>

      <label>Password:</label>
      <input type="password" name="pword"><br><br>

      <button onclick="loginButton()">login</button>
    </div>

    <div id="sign-up" class="border centered">
      <p class="error" name="error"></p>

      <label>Email:</label>
      <input type="text" name="email"><br><br>

      <label>Username:</label>
      <input type="text" name="uname"><br><br>

      <label>Password:</label>
      <input type="password" name="pword"><br><br>

      <label>Repeat Password:</label>
      <input type="password" name="rword"><br><br>

      <button onclick="signupButton()">Sign Up</button>
    </div>
    <button onclick="showTeamSelectionNotification()">Select Team</button>
  </body>
</html>