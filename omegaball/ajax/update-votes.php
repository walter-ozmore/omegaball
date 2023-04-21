<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $conn = connectDB("newOmegaball");

  // Decode json from vote.php into an array. This hold the # of new votes;
  $array = json_decode($_POST["q"], true);

  // Get the current user id
  $uid = getCurrentUser();

  // Get the other information from the passed array
  $voteId = array["voteId"];
  $numVotes = array["numVotes"];


  // Make the query and run it
  $query = "INSERT INTO VoteQueue VALUES ('$uid', '$voteId', '$numVotes')";
  runQuery($conn, $query);
?>