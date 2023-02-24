<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";


  /**
   * Generates a random float between 0 (inclusive) and 1 (exclusive).
   *
   * @return float
   */
  function random() {
    return (float)rand() / (float)getrandmax();
  }


  /**
   * Creates the display name of the player given
   *
   * @todo Update this function to work with new global data object
   *
   * @param player
   * @param noExtra prevent extra information from being shown, such as when a
   * player is out of the game they are crossed out
   */
  function getPlayerDisplayName($player, $noExtra=false) {
    $player = getPlayerFromId( $player );
    $team = getTeamFromPlayerId( $player );

    $playerName = $player["playerName"];
    $teamColor = $team["teamColor"];
    $extra = ($player["inGame"] || $noExtra)? "" : "text-decoration: line-through white;";
    $displayName = "<span style='color: $teamColor;$extra'>$playerName</span>";

    return $displayName;
  }

  function loadData() {
    $conn = connectDB("newOmegaball");
    $data = [];

    // Restrict data to this league
    $league = "The Alphaleague";

    $data["teams"] = [];
    $query = "SELECT * FROM Team WHERE league='$league';";
    $result = runQuery($conn, $query);
    while ($row = $result->fetch_assoc()) {
      $teamAcronym = $row["acronym"];
      $data["teams"][$teamAcronym] = $row;
    }


    // Add players to each teams given
    foreach($data["teams"] as $teamObject) {
      // Grab acronym, there should be a better way to do this
      $teamAcronym = $teamObject["acronym"];

      // Initalize array for players
      $data["teams"][$teamAcronym]["players"] = [];

      // Run query
      $query = "SELECT * FROM Player WHERE team='$teamAcronym';";
      $result = runQuery($conn, $query);
      while ($row = $result->fetch_assoc()) {
        $id = $row["playerName"];
        $data["teams"][$teamAcronym]["players"][$id] = $row;
      }
    }

    return $data;
  }

  function saveData($data) {
    $conn = connectDB("newOmegaball");

    $multiQuery = [];
    // if teams
    foreach($data["teams"] as $teamAcronym => $team) {

      //All team variables
      $teamColor = $data["teams"][$teamAcronym]["teamColor"];
      $division = $data["teams"][$teamAcronym]["division"];
      $league = $data["teams"][$teamAcronym]["league"];
      $wins = $data["teams"][$teamAcronym]["wins"];
      $loses = $data["teams"][$teamAcronym]["loses"];
      $ties = $data["teams"][$teamAcronym]["ties"];
      $flavorText = $data["teams"][$teamAcronym]["flavorText"];
      $players = $data["teams"][$teamAcronym]["players"];

      $multiQuery[] = "UPDATE Team SET
        teamColor = '$teamColor',
        division = '$division',
        league = '$league',
        wins = $wins,
        loses = $loses,
        ties = $ties,
        flavorText = '$flavorText'
        WHERE acronym='$teamAcronym';";

        foreach($players as $playerName => $player) {
          // All player variables
          $animus = $players[$playerName]["animus"];
          $authority = $players[$playerName]["authority"];
          $catches = $players[$playerName]["catches"];
          $celerity = $players[$playerName]["celerity"];
          $dodges = $players[$playerName]["dodges"];
          $endurance = $players[$playerName]["endurance"];
          $entropy = $players[$playerName]["entropy"];
          $foresight = $players[$playerName]["foresight"];
          $hits = $players[$playerName]["hits"];
          $hustle = $players[$playerName]["hustle"];
          $incorporeality = $players[$playerName]["incorporeality"];
          $markovianism = $players[$playerName]["markovianism"];
          $misses = $players[$playerName]["misses"];
          $sublimity = $players[$playerName]["sublimity"];
          $team = $players[$playerName]["team"];
          $tendons = $players[$playerName]["tendons"];
          $totalHands = $players[$playerName]["totalHands"];

          $multiQuery[] = "UPDATE Player SET
            animus = $animus,
            authority = $authority,
            catches = $catches,
            celerity = $celerity,
            dodges = $dodges,
            endurance = $endurance,
            entropy = $entropy,
            foresight = $foresight,
            hits = $hits,
            hustle = $hustle,
            incorporeality = $incorporeality,
            markovianism = $markovianism,
            misses = $misses,
            sublimity = $sublimity,
            team = '$team',
            tendons = $tendons,
            totalHands = $totalHands
            WHERE playerName = '$playerName';";
        }
    }


    // Reform multiquery in to a string
    $multiQueryStr = "";
    foreach($multiQuery as $query)
      $multiQueryStr .= $query . "<br>";

    return $multiQueryStr;
  }
?>