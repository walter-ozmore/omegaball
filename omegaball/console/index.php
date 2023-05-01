<!DOCTYPE html>
<html>
  <head>
    <title>Title</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

    <style>
      .box a {
        display: block;
        margin-left: 1em;
      }
      .box h2 {
        margin: .5em 0;
        padding: 0em;
        border-bottom: solid;
        border-color: gray;
        width: 100%;
      }
    </style>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>

  <body>
    <div class="box border">
      <h2>Admin Console</h2>
      <a href="/omegaball/console/names.php">Suggested Names</a>
      <a href="/omegaball/console/game-manager.php">Game Manager</a>

      <h2>Test Pages</h2>
      <a href="/omegaball/test/basic-page">Basic Page</a>
      <a href="/omegaball/test/account-test">Account Test</a>
      <a href="/omegaball/test/add-game">Add Game</a>
      <a href="/omegaball/test/test-console">Test Console</a>
      <a href="/omegaball/test/view-tracker">View Tracker</a>
    </div>
  </body>
</html>