<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  $args = json_decode($_POST["q"], true);

  // Connect to database
  $conn = connectDB("newOmegaball");

  // Create a return object to append to later
  $returnObj = [];

  // Get the data
  $offset = $args["offset"]; // Set offset to a variable to make it easier to use
  $query = "SELECT playerName, inGame, position FROM PlayerName ORDER BY inGame ASC, playerName ASC LIMIT 25 OFFSET $offset";

  $result = runQuery($conn, $query);

  while($row = $result -> fetch_assoc()){
    $returnObj["data"][]  = $row;
  }

  // Get the max count
  $query = "SELECT COUNT(playerName) AS count FROM PlayerName";
  $result = $conn->query($query);
  $row = $result->fetch_assoc();
  $returnObj["count"] = $row["count"];

  // Convert return object to a string in a json format
  echo json_encode($returnObj);
?>