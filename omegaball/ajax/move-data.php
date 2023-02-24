<?php
   require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
   require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $connOld = connectDB("omegaball");
  $connNew = connectDB("newOmegaball");

  $teams = [];
  $query = "SELECT teamName, acronym FROM Team";
  $result = runQuery($connOld, $query);
  while ($row = $result->fetch_assoc()) {
    $teams[$row["teamName"]] = $row["acronym"];
  }

  $multiQuery = [];

  $query = "SELECT * FROM Player";
  $result = runQuery($connOld, $query);
  while ($row = $result->fetch_assoc()) {
    $args = [];
    $args["playerName"] = $row["playerName"];
    $args["tendons"] = $row["tendons"];
    $args["animus"] = $row["animus"];
    $args["foresight"] = $row["foresight"];
    $args["sublimity"] = $row["sublimity"];
    $args["markovianism"] = $row["markovianism"];
    $args["authority"] = $row["authority"];
    $args["endurance"] = $row["endurance"];
    $args["celerity"] = $row["celerity"];
    $args["hustle"] = $row["hustle"];
    $args["incorporeality"] = $row["incorporeality"];
    $args["totalHands"] = $row["totalHands"];
    $args["entropy"] = $row["entropy"];
    $args["team"] = $teams[ $row["teamName"] ];

    // Build columns section and the values secation
    $columns = "(";  $values = "(";
    foreach($args as $key => $value) {
      $columns .= "$key, ";

      if( is_numeric($value) ) {
        $values .= "$value, ";
        continue;
      }
      $values .= "'$value', ";
    }
    $columns = substr($columns, 0, strlen($columns) - 2) . ")";
    $values  = substr($values , 0, strlen($values ) - 2) . ")";

    $query = "INSERT INTO Player $columns VALUES $values;";
    $multiQuery .= $query;
    echo "$query<br>";
  }
?>