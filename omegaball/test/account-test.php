<!DOCTYPE html>
<html>
  <head>
    <title>League</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

    <script>
      function logUser(user) {
        let row = `
          <td>${user["uid"]}</td>
          <td>${user["username"]}</td>
          <td>${user["team"]}</td>
          <td>${user["currency"]}t</td>
        `;

        document.getElementById("table").appendChild( mkEle("tr", row) );
        console.log(user);
      }

      onWindowLoad(function() {
        Accounts.loadAccount(8, logUser);
        Accounts.loadAccount(16, logUser);
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <table id="table">
      <tr>
        <th>UID</th>
        <th>Username</th>
        <th>Team</th>
        <th>Teeth</th>
      </tr>
    </table>
  </body>
</html>