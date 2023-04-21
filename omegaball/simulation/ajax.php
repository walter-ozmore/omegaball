<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/simulate-game.php";
  $args = json_decode($_POST["q"], true);
  $re = runGame($args);
  echo json_encode( $re );
?>