<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];

  $conn = connectDB("newOmegaball");
  
  $query = "SELECT playerName, inGame, position FROM PlayerName ORDER BY inGame ASC, playerName ASC";

  $result = runQuery($conn, $query);

  while($row = $result -> fetch_assoc()){
    $returnObj["playerName"] = $row["playerName"];
    $returnObj["inGame"] = $row["inGame"];
    $returnObj["position"] = $row["position"];
  }

  echo json_encode($returnObj);
?>