<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];

  $conn = connectDB("newOmegaball");

  $startTime = $_POST["startTime"];
  $endTime = $_POST["endTime"];

  $query = "SELECT title, voteType, image, votes, description FROM Vote
    WHERE startTime = '$startTime' AND endTime = '$endTime';";

  while ($row = $result->fetch_assoc()) {
    $returnObj[] = $row;
  }

  echo json_encode( $returnObj );
?>