<?php
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $args = json_decode($_POST["q"], true);

  if(strcmp($args["action"], "generate") == 0) {
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/simulate-game.php";
    $response = runGame($args);
    echo json_encode( $response );
  }

  // Generates a game and adds it to the game list
  if(strcmp($args["action"], "gna") == 0) {
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/simulate-game.php";
    $conn = connectDB("newOmegaball");
    $response = runGame($args);
    $title = "Missing Title";
    $releaseTime = time();
    $encode = addslashes( json_encode($response) );
    $query = "INSERT INTO Game (json, title, releaseTime) VALUES (\"$encode\", \"$title\", $releaseTime);";
    echo $query;
    runQuery($conn, $query);
    // echo json_encode($re);
  }


  if(strcmp($args["action"], "loadGame") == 0) {
    $conn = connectDB("newOmegaball");
    $query = "SELECT json FROM Game WHERE gameID=".$args["gameID"];
    $result = runQuery($conn, $query);
    while ($row = $result->fetch_assoc()) {
      $json = $row["json"];
    }
    echo $json;
  }


  if(strcmp($args["action"], "loadGameTitles") == 0) {
    $conn = connectDB("newOmegaball");
    $query = "SELECT gameID, title FROM Game";
    $result = runQuery($conn, $query);

    $json = [];
    while ($row = $result->fetch_assoc()) {
      $json[] = $row;
    }
    echo json_encode( $json );
  }
?>