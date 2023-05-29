<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $args = json_decode($_POST["q"], true);
  // echo var_dump($args);

  $returnObj = [];
  $a = [];

  $conn = connectDB("newOmegaball");

  $offset = $args["offset"];

  $query = "SELECT playerName, inGame, position FROM PlayerName ORDER BY inGame ASC, playerName ASC LIMIT 25 OFFSET $offset";

  $result = runQuery($conn, $query);

  while($row = $result -> fetch_assoc()){
    $a["playerName"] = $row["playerName"];
    $a["inGame"] = $row["inGame"];
    $a["position"] = $row["position"];

    $returnObj[]  = $a;
  }

  echo json_encode($returnObj);
?>