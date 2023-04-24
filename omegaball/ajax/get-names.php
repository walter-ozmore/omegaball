<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $returnObj = [];
  $a = [];

  $conn = connectDB("newOmegaball");

  $query = "SELECT playerName, inGame, position FROM PlayerName ORDER BY inGame ASC, playerName DESC LIMIT 25";

  $result = runQuery($conn, $query);

  while($row = $result -> fetch_assoc()){
    $a["playerName"] = $row["playerName"];
    $a["inGame"] = $row["inGame"];
    $a["position"] = $row["position"];

    $returnObj[]  = $a;
  }

  echo json_encode($returnObj);
?>