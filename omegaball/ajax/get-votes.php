<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];

  $conn = connectDB("newOmegaball");

  $a = json_decode($_POST["q"]);

  $query = "SELECT id, title, voteType, image, votes, description FROM Vote
    WHERE startTime = '$a[0]' AND endTime = '$a[1]';";

  $result = runQuery($conn, $query);
  while ($row = $result->fetch_assoc()) {
    $returnObj[] = $row;
  }

  echo json_encode( $returnObj );
?>