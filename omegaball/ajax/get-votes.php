<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];

  $conn = connectDB("newOmegaball");
  $array = json_decode($_POST["q"], true);

  $season = $array["season"];

  $query = "SELECT id, title, voteType, image, votes, description FROM Vote
    WHERE season = '$season';";

  $result = runQuery($conn, $query);
  while ($row = $result->fetch_assoc()) {
    $returnObj[] = $row;
  }

  echo json_encode( $returnObj );
?>