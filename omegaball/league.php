<!DOCTYPE html>
<html>
  <head>
    <title>League</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

    <!-- Style sheets -->
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
      }

      .hidden {
        display: none;
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
        /* .grid-container div {
          display: block;
        } */
      }
    </style>

    <!-- Scripts -->
    <script src="/omegaball/scripts/league.js"></script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."\\omegaball\\res\\header.php"; ?>
  </header>
  <body>
    <div class="grid-container">
      <!-- Leagues/Divisions/Team -->
      <div id="division" class="border hidden"></div>

      <!-- Team Info -->
      <div id="team" class="border hidden"></div>

      <!-- Player -->
      <div id="player" class="border hidden"></div>
    </div>
  </body>
</html>