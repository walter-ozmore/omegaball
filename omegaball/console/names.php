<!DOCTYPE html>
<html>
  <head>
    <title>League</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>
    
    <style>
        .table {
            width: auto; 
            height: 500px; 
            margin: 2em auto auto auto;
            /* overflow-x: scroll; */
            overflow-y: scroll;
            border: 3px solid;
        }

        .name, th, td {
            border: 1px solid;
            width: 100%;
            border-collapse: collapse;
            
        }

        .name th {
            width: 33%;
        }
        </style>

    </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
        <div class = table>
        <table id = "names" class = name>
            <thead><tr><th>Name</th><th>inGame</th><th>Position</th></tr></thead>
            <tbody>
            </tbody>
        </table>
  </body>
</html>