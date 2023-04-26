<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];
  $conn = connectDB("newOmegaball");

  // Decode json from vote.php into an array
  $array = json_decode($_POST["q"], true);

  // Retrieve the season
  $season = $array["season"];

  // Getting the required information from all votes in current season
  $query = "SELECT id, title, voteType, image, votes, description FROM Vote
    WHERE season = '$season';";

  // Run query and put into the return obj
  $result = runQuery($conn, $query);
  while ($row = $result->fetch_assoc()) {
    $returnObj[] = $row;
  }

  echo json_encode( $returnObj );
?>