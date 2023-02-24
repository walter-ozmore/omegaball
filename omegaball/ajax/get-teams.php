<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];

  $conn = connectDB("omegaball");

  $league = ( isset($_GET["league"]) )? $_GET["league"] : "The Alphaleague";
  $query = "SELECT * FROM Team WHERE league='$league'";
  $result = runQuery($conn, $query);
  while ($row = $result->fetch_assoc()) {
    $division = $row["division"];

    if( !array_key_exists( $division, $returnObj ) ) {
      $returnObj[ $division ] = [];
    }

    $returnObj[ $division ][] = $row;
  }

  echo json_encode( $returnObj );
?>