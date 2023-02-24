<!DOCTYPE html>
<html>
  <head>
    <title>Omegaball || League</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

    <!-- Style sheets -->
    <link rel="stylesheet" href="/omegaball/res/master.css">
    <style>
      h3 {
        margin-bottom: 0px;
      }

      .grid-container {
        display: grid;
        grid-auto-flow: row;
      }

      .grid-container div {
        padding: .5em;
        /* display: none; */
      }


      /* Desktop */
      @media screen and (min-width: 900px) {
        /*
          Display vertically if there isnt enough room
          This should be changed to tabs later
         */
        .grid-container {
          grid-auto-flow: column;
          grid-auto-columns: minmax(0, 1fr);
        }
        .grid-container div {
          display: block;
        }
      }
    </style>

    <!-- Scripts -->
    <script src="/omegaball/res/lib.js"></script>
    <script src="/omegaball/scripts/league.js"></script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."\\omegaball\\res\\header.php"; ?>
  </header>
  <body>
    <div class="grid-container">
      <!-- Leagues/Divisions/Team -->
      <div id="division" class="border"></div>

      <!-- Team Info -->
      <div id="team" class="border"></div>

      <!-- Player -->
      <div id="player" class="border"></div>
    </div>
  </body>
</html>