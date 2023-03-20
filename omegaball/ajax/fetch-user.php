<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $uid = (isset($_POST["uid"]))? $_POST["uid"] :-1;
  $returnObj = [];

  $conn = connectDB("newOmegaball");
  $query = "SELECT * FROM User WHERE uid='$uid'";
  $result = runQuery($conn, $query);
  $returnObj = $result->fetch_assoc();

  $conn = connectDB("account");
  $query = "SELECT username FROM User WHERE uid='$uid'";
  $result = runQuery($conn, $query);
  $row = $result->fetch_assoc();
  if($row == null) exit();
  $returnObj["username"] = $row["username"];

  echo json_encode( $returnObj );
?>