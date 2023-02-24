<!DOCTYPE html>
<html>
  <head>
    <?php
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>
    <!-- Remove this later -->
    <link rel="stylesheet" href="/omegaball/old/res/master.css">

    <script>
      function gen() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            let txt = this.responseText;
            document.getElementById("output").innerHTML = txt;
          }
        };
        xhttp.open("GET", "/omegaball/ajax/name-gen.php", true);
        xhttp.send();
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <p id="output"></p>
    <button onclick="gen()">Submit</button>
  </body>
</html>