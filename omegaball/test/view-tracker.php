<!DOCTYPE html>
<html>
  <head>
    <title>Test</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

    <script>
      function updateTime() {
        // Get the current time
        var currentTime = new Date();

        // Calculate the difference between the current time and the start time
        var timeOnPage = currentTime - startTime;

        // Convert the time to seconds
        timeOnPage = Math.round(timeOnPage / 1000);

        // Update the page with the time on page
        document.getElementById("timeOnPage").innerHTML = "You've been on this page for " + timeOnPage + " seconds.";
      }

      var startTime = new Date();

      onWindowLoad(function() {
        Accounts.loadAccount(8);

        setInterval(updateTime, 1000);
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <p id="timeOnPage"></p>
  </body>
</html>