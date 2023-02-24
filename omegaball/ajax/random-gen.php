<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  function random() {
    return (float)rand() / (float)getrandmax();
  }

  $superQuery = "";
  $conn = connectDB("omegaball");
  $query = "SELECT * FROM Player";
  $result = runQuery($conn, $query);
  while ($row = $result->fetch_assoc()) {
    $id = $row["playerID"];

    // Power
    $tendons = random();
    $animus = random();

    // Accuracy
    $foresight = random();
    $sublimity = random();
    $markovianism = random();

    // Defense
    $authority = random();
    $endurance = random();

    // Finesse
    $celerity = random();
    $hustle = random();
    $incorporeality = random();

    // Misc
    $totalHands = random();
    $entropy = random();

    $superQueryRow = "UPDATE Player SET tendons=$tendons, animus=$tendons, foresight=$foresight, sublimity=$sublimity, markovianism=$markovianism, authority=$authority, endurance=$endurance, celerity=$celerity, hustle=$hustle, incorporeality=$incorporeality, totalHands=$totalHands, entropy=$entropy WHERE playerID=$id;";
    $superQuery .= $superQueryRow;
  }
  // echo $superQuery;
  // $conn -> multi_query($superQuery);
?>