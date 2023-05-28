<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $conn = connectDB("newOmegaball");

  // Decode json from vote.php into an array. This hold the # of new votes;
  $array = json_decode($_POST["q"], true);

  // Get the current user id
  $uid = getCurrentUser()["uid"];

  // Get information from array
  $numVotes = $array["numVotes"];

  // Create return obj
  $returnObj = [];
  $cost = 10;

  // Check to see if they have enough funds
  $query = "SELECT currency FROM User WHERE uid = '$uid';";
  $result = runQuery($conn, $query);
  while ($row = $result->fetch_assoc()) {
    if ($row["currency"] <= $numVotes * $cost) {
      // Return error 1 if not enough funds
      $returnObj[] = 1;
      break;
    }

    // Update their number of votes
    $query = "UPDATE User SET votes = '$numVotes' WHERE uid = '$uid';";
    runQuery($conn, $query);

    // Extract funds
    $query = "UPDATE User SET currency = currency - ('$numVotes' * $cost) WHERE uid = '$uid';";
    runQuery($conn, $query);
    $returnObj[] = 0;
  }

  // Return obj
  echo json_encode( $returnObj );
?>