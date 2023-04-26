<?php
  // Start session to save the loaded game data
  session_start();

  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  function setGame($obj) {
    $_SESSION["game"] = $obj;
  }

  function resetGame() {
    unset( $_SESSION["game"] );
  }

  function saveGameToSQL() {
    $gameObj = $_SESSION["game"];

    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/simulate-game.php";
    $conn = connectDB("newOmegaball");
    $gameObj = $_SESSION["game"];
    $title = "Missing Title";
    $releaseTime = time();
    // TODO reduce for sql
    $encode = addslashes( json_encode($gameObj) );
    $query = "INSERT INTO Game (json, title, releaseTime) VALUES (\"$encode\", \"$title\", $releaseTime);";
    runQuery($conn, $query);
  }

  function generate($args) {
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/simulate-game.php";
    $gameObj = runGame($args);

    // Save game to session in case to user want to save it to SQL later
    setGame($gameObj);

    echo json_encode( $gameObj );
    return $gameObj;
  }

  function load($gameID) {
    // echo "here";
    $conn = connectDB("newOmegaball");
    $query = "SELECT json FROM Game WHERE gameID=$gameID";
    $result = runQuery($conn, $query);
    while ($row = $result->fetch_assoc()) {
      $jsonStr = $row["json"];
    }
    echo $jsonStr;
  }

  function loadTitles() {
    $conn = connectDB("newOmegaball");
    $query = "SELECT gameID, title FROM Game";
    $result = runQuery($conn, $query);

    $json = [];
    while ($row = $result->fetch_assoc()) {
      $json[] = $row;
    }
    echo json_encode( $json );
  }

  function equals($str_a, $str_b) { return strcmp($str_a, $str_b) == 0; }

  $args = json_decode($_POST["q"], true);

  $actions = $args["actions"];
  foreach($actions as $action) {
    if(equals($action, "generate"      )) generate($args["gameArgs"]);
    if(equals($action, "save"          )) saveGameToSQL();
    if(equals($action, "load"          )) load($args["gameID"]);
    if(equals($action, "loadTitles"    )) loadTitles();
  }
?>