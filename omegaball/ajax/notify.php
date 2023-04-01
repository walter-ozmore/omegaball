<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  function seasonTeamWin($teamAcronym) {
    global $returnObj, $conn;

    $query = "SELECT teamName, winButtonText FROM Team WHERE acronym=\"$teamAcronym\";";
    $row = runQuery($conn, $query)->fetch_assoc();

    $returnObj[] = [
      "code"=>2,
      "acronym"=>$teamAcronym,
      "teamName"=>$row["teamName"],
      "buttonText"=>(($row["winButtonText"] == null)? "OKAY" : $row["winButtonText"])
    ];
  }

  $returnObj = [];

  // Load current user
  $user = getCurrentUser();
  if($user == null) {
    echo json_encode( $returnObj );
    exit();
  }
  $uid = $user["uid"];

  $conn = connectDB("newOmegaball");
  $query = "SELECT team FROM User WHERE uid=$uid LIMIT 1;";
  $teamAcronym = runQuery($conn, $query)->fetch_assoc()["team"];

  if($teamAcronym == null) {
    // No user has no selected team, make them choose one
    $returnObj[] = ["code"=>1];
  }

  // seasonTeamWin("STYX");

  echo json_encode( $returnObj );
?>