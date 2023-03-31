<!DOCTYPE html>
<html>
  <head>
    <title>Test</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <?php
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/simulate-game.php";

      $args = json_decode('{"teams":["EVRG","HEAV"],"rules":{"displayPickupMessages":1,"useOutPoints":1,"defaultOutPointsAmount":"2","speed":"80"}}', true);
      $outputObj = runGame($args);

      $str = addslashes(json_encode($outputObj));
      // echo $str."<br>";
      echo "<script>let obj = JSON.parse('$str'); console.log(obj);</script>";
    ?>
    <p>Job Done</p>
  </body>
</html>