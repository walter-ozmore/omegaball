<!DOCTYPE html>
<html>
  <head>
    <title>League</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>

    <script src="script.js"></script>

    <style>
      .table {
        width: auto;
        height: 500px;
        margin: 2em auto auto auto;
        overflow-y: scroll;
        border: 3px solid;
      }

      .data, th, td {
        border: 1px solid;
        width: 100%;
        border-collapse: collapse;
      }

      .data th {
        width: 33%;
      }
    </style>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <table id="data" class="data">
      <tr>
        <th>Name</th>
        <th>inGame</th>
        <th>Position</th>
      </tr>
    </table>

    <button onclick = "changePage(-20)"> Back </button>
    <button onclick = "changePage( 20)"> Forward </button>
  </body>
</html>