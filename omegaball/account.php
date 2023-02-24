<!DOCTYPE html>
<html>
  <head>
    <title>Account | Omegaball</title>
    <?php
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/newlib.php";

      function createSelector($id) {
        echo "<select id='$id'>";
        $conn = connectDB("omegaball");
        $query = "SELECT teamName FROM Team WHERE league='The Alphaleague'";
        $result = runQuery($conn, $query);
        while ($row = $result->fetch_assoc()) {
          $teamName = $row["teamName"];
          echo "<option value='$teamName'>$teamName</option>";
        }
        echo "</select>";
      }
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

    <link rel="stylesheet" href="/omegaball/res/master.css">

    <script>
      function login() {
        var http = new XMLHttpRequest();
        var url = '/account/ajax/login.php';
        http.open('POST', url, false);

        //Send the proper header information along with the request
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        http.onreadystatechange = function() {
          if(http.readyState == 4 && http.status == 200) {
            var obj = JSON.parse( http.responseText );
            console.log( obj.message );

            if( obj.code != 0 ) {
              document.getElementById("error").innerHTML = obj.message;
            }
          }
        }

        let uname = document.getElementById("uname").value;
        let pword = document.getElementById("pword").value;

        http.send("uname="+uname+"&pword="+pword);
      }

      function signup() {}

      <?php
        if($currentUser == null) {
          echo "
            login();
          ";
        }
      ?>
    </script>

    <style>
      .linksVertical a{
        display: block;
      }
    </style>
  </head>

  <header>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php";
    ?>
  </header>

  <body>
    <div id="login" style="display: none">
      <p class="error"></p>

      <label>Username</label>
      <input type="text" name="uname">

      <label>Password</label>
      <input type="text" name="pword">

      <button>login</button>
    </div>


    <div id="signup" style="display: none">
      <label>Email</label>
      <input type="text" name="email">

      <label>Username</label>
      <input type="text" name="uname">

      <label>Password</label>
      <input type="text" name="pword">

      <label>Repeat Password</label>
      <input type="text" name="rword">

      <button>Sign Up</button>
    </div>


    <div class="linksVertical">
      <a>Create Game</a>
    </div>

    <div style="display: block">
      <div>
        <h2>Teams</h2>
        <div>
          <p>The Agartha Spelunkers</p>
          <p>The Elysium Orchards</p>
        </div>
        <?php createSelector("teamSelector"); ?>
        <button>+</button>
      </div>

      <label>Start Date</label>
      <input type="date"><br>

      <label>Start Time</label>
      <input type="time">
    </div>
  </body>
</html>