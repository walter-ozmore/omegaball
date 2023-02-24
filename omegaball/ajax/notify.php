<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];

  $conn = connectDB("omegaball");
  $query = "SELECT teamName, acronym, teamColor FROM Team ORDER BY RAND() LIMIT 5";
  $result = runQuery($conn, $query);

  while ($row = $result->fetch_assoc()){
    $returnObj = $row;
  }

  echo json_encode( $returnObj );
?>