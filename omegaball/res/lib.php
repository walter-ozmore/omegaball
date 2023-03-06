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
   * Gets the player that has a primary key of the argument from the global data array.
   *
   * @param mixed $playerID The primary key of the player
   *
   * @return array An array containing the details of the
   * player
   */
  function getPlayer($playerID) {
    global $data;

    foreach( $data["teams"] as $team )
      foreach( $team["players"] as $playerIndex => $player )
        if( compare($playerIndex, $playerID) )
          return $player;
  }

  /**
   * Gets the team array that matches the given arguments
   * from the global data array.
   *
   * @param array $args The data that relates the team
   *
   * @return array An array containing the details of the team
   */
  function getTeam($args) {
    global $data;

    if( array_key_exists("playerIndex", $args) ) {
      $playerID = $args["playerIndex"];

      foreach( $data["teams"] as $team )
        foreach( $team["players"] as $playerIndex => $player )
          if( compare($playerIndex, $playerID) )
            return $team;
    }

    if( array_key_exists("playerName", $args) ) {
      $playerID = $args["playerName"];

      foreach( $data["teams"] as $team )
        foreach( $team["players"] as $playerIndex => $player )
          if( compare($playerIndex, $playerID) )
            return $team;
    }
  }


  /**
   * Save the given array back to the main data object
   *
   * @param mixed the array to be saved {player, save}
   */
  function save($arg) {
    global $data;

    if( array_key_exists("playerName", $arg) ) {
      foreach( $data["teams"] as $teamIndex => $team )
        foreach( $team["players"] as $playerIndex => $player )
          // if( $arg["playerName"] == $playerIndex ) {
          if( compare( $arg["playerName"], $playerIndex ) ) {
            $data["teams"][$teamIndex]["players"][$playerIndex] = $arg;
            return;
          }
      return;
    }

    if( array_key_exists("acronym", $arg) ) {
      foreach( $data["teams"] as $teamIndex => $team )
        if( compare( $arg["acronym"], $teamIndex ) ) {
          $data["teams"][$teamIndex] = $arg;
          return;
        }
      return;
    }
  }


  /**
   * Compare two values $a and $b
   *
   * @param mixed $a First value to compare
   * @param mixed $b Second value to compare
   *
   * @return bool true if the values are equal
   */
  function compare($a, $b) {
    if( is_string( $a ) ) // String
      return strcmp($a,  $b) == 0;
    else // Number
      return $a == $b;
  }


  /**
   * Creates the display name of the player given
   *
   * @param player
   * @param noExtra prevent extra information from being shown, such as when a
   * player is out of the game they are crossed out
   */
  function getPlayerDisplayName($playerObj, $extra=true) {
    $teamObj = getTeam( ["playerIndex"=>$playerObj["playerName"]] );

    $playerName = $playerObj["playerName"];
    $teamColor = $teamObj["teamColor"];
    $extra = ( !array_key_exists("inGame", $playerObj) || $playerObj["inGame"] || $extra)? "" : "text-decoration: line-through white;";
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