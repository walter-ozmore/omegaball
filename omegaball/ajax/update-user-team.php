<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $uid = $_POST["uid"];
  // $ukey = $_POST["key"];
  $team = $_POST["team"];

  // Check if the key in the users table is right
  // Update the key
  $conn = connectDB("newOmegaball");
  $query = "UPDATE User SET team='$team' WHERE uid=$uid";
  runQuery($conn, $query);

  echo "Team updated to $team";
?>