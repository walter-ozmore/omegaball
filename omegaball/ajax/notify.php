<?php
  /**
   * Check for any notifications a user need to see then creates a JSON
   * object of the notifications and returns it.
   *
   * @author Walter Ozmore
   * @
   */
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];

  $conn = connectDB("newOmegaball");
  $query = "SELECT teamName, acronym, teamColor FROM Team ORDER BY RAND() LIMIT 5";
  $result = runQuery($conn, $query);

  while ($row = $result->fetch_assoc()){
    $returnObj = $row;
  }

  echo json_encode( $returnObj );
?>