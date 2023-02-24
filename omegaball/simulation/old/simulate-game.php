<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/util.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/actions.php";

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";


  /**
   * Check if the game is over by any means
   *
   * @return boolean True if the game is over
   */
  function isGameDone() {
    global $teams, $ballsOnGround, $outputObj;

    $teamsStillInGame = [];

    // Check if there is only one team left
    for($x = 0;$x < sizeof($teams);$x++) {
      $team = $teams[$x];
      $players = $team["players"];
      $playersInGame = 0;

      for($y = 0;$y < sizeof($players);$y++) {
        $player = $players[$y];
        if($player["inGame"])
          $playersInGame += 1;
      }

      if($playersInGame > 0)
        $teamsStillInGame[] = $team;
    }

    if( sizeof($teamsStillInGame) == 0 ) {
      $outputObj["game"][]["message"] = "<center><h2>No teams remain, tie</h2></center>";
      return true;
    }

    if( sizeof($teamsStillInGame) == 1 ) {
      $teamName = $teamsStillInGame[0]["teamName"];
      $outputObj["game"][]["message"] = "<center><h2>$teamName wins, Game Over</h2></center>";
      return true;
    }

    return false;
  }


  /**
   * Prints round info to the screen.
   *
   * Info Printed: Teams, Players in game, Balls on the ground
   *
   * This is used for debugging
   */
  function displayRoundInfo() {
    global $teams, $ballsOnGround;
    echo "
      <div class='roundSummary'>
        <div class='teams' style='margin-bottom: 1em;'>
    ";

    for($x = 0;$x < sizeof($teams);$x++) {
      echo "<div>";
      $team = $teams[$x];
      $players = $teams[$x]["players"];
      $teamColor = $team["teamColor"];
      echo "<h2 style='color: $teamColor'>". $team["teamName"] ."</h2> ";

      for($y = 0;$y < sizeof($players);$y++) {
        $player = $players[$y];
        if( $player["inGame"] == false ) continue;
        $displayName = getDisplayName( $player );

        echo "<p>$displayName</p>";
      }
      echo "</div>";
    }

    echo "
        </div>
        <p>Balls on field: $ballsOnGround</p>
      </div>
    ";
  }


  /**
   * Loads the given team and all players from the SQL database
   *
   * @param teamName The name of the team that will be loaded
   */
  function loadTeam($teamName) {
    global $teams, $conn;

    $team = [];
    $team["teamName"] = $teamName;

    $query = "SELECT * FROM Team WHERE teamName='". addslashes($teamName) ."';";
    $result = runQuery($conn, $query);
    while ($row = $result->fetch_assoc()) {
      $team["teamColor"] = $row["teamColor"];
      $team["teamID"] = $row["uID"];
    }

    $query = "SELECT * FROM Player WHERE teamName='". addslashes($teamName) ."' ORDER BY RAND() LIMIT 5;";
    $result = runQuery($conn, $query);
    while ($row = $result->fetch_assoc()) {
      // Set default local vars
      $row["heldBalls"] = 0;
      $row["inGame"] = true;
      $team["players"][] = $row;
    }

    return $team;
  }


  /**
   * Creates the actionQueue and returns it by adding players to the list ordered
   * by the speed of the player
   *
   * But I am lazy right now and they are added in the order they come in
   */
  function createActionQueue() {
    global $teams;
    $actionQueue = [];

    // Add players to action queue by speed
    for($x = 0;$x < sizeof($teams);$x++) {
      $team = $teams[$x];
      $players = $teams[$x]["players"];

      for($y = 0;$y < sizeof($players);$y++) {
        $player = $players[$y];
        if( $player["inGame"] == false ) continue;

        // Add player to queue
        $actionQueue[] = $player["playerID"];
      }
    }

    // Sort
    $n = sizeof($actionQueue);

    for($i = 0;$i < $n;$i++) {
      for($j = 0;$j < $n - $i; $j++) {
        $s1 = getPlayerFromId( $actionQueue[$j] )["hustle"];
        $s2 = getPlayerFromId( $actionQueue[$j+1] )["hustle"];

        if($s1 > $s2) {
          $t = $actionQueue[$j];
          $actionQueue[$j] = $actionQueue[$j + 1];
          $actionQueue[$j+1] = $t;
        }
      }
    }

    return $actionQueue;
  }


  /**
   * Creates the display name of the player given
   *
   * @param player
   * @param noExtra prevent extra information from being shown, such as when a
   * player is out of the game they are crossed out
   */
  function getDisplayName($player, $noExtra=false) {
    $player = getPlayerFromId( $player );
    $team = getTeamFromPlayerId( $player );

    $playerName = $player["playerName"];
    $teamColor = $team["teamColor"];
    $extra = ($player["inGame"] || $noExtra)? "" : "text-decoration: line-through white;";
    $displayName = "<span style='color: $teamColor;$extra'>$playerName</span>";

    return $displayName;
  }


  /**
   * Running this function will make the game move by one round, 6-10 secs
   */
  function runRound() {
    global $teams, $ballsOnGround;
    $actionQueue = createActionQueue();

    for($x = 0; $x < sizeof($actionQueue); $x++) {
      if(isGameDone()) return false;
      $action = $actionQueue[$x];

      // Assume this is a player for now this should be changed later
      $playerId = $action;
      $player = getPlayerFromId( $playerId );
      $team = getTeamFromPlayerId( $playerId );

      $playerName = $player["playerName"];
      $teamColor = $team["teamColor"];
      $displayName = getDisplayName($player);

      // echo "<p>Player $displayName is taking their turn</p>";

      if( $player["heldBalls"] == 0 && $ballsOnGround > 0 ) {
        pickupBall($player);
        continue; // Turn done
      }

      // Throw it
      if( $player["heldBalls"] > 0 ) {
        throwBall($player);
        continue;
      }

      // Hand it off
    }

    return true;
  }


  function displayObj($obj, $key=null) {
    $str = "";
    if($key != null) $str .= "$key: ";

    $str .= "[";
    foreach($obj as $key => $value) {
      // if( strcmp($key, "players") == 0 ) continue;

      if( is_array($value) ) {
        $str .= "<div class='indent'>";
        displayObj($value, $key);
        $str .= "</div>";
      } else {
        $str .= "<div class='indent'><xmp>$key: $value</xmp></div>";
      }
    }
    $str .= "]";
    return $str;
  }

  /**
   * Remove excess data to reduce package size
   */
  function reduceForDisplay($teams) {
    for($x = 0;$x < sizeof($teams);$x++) {
      $team = $teams[$x];
      $players = $teams[$x]["players"];

      for($y = 0;$y < sizeof($players);$y++) {
        $player = $players[$y];
        $newPlayer = [];
        $copyList = [
          "playerID",
          "playerName"
        ];

        for($z = 0;$z < sizeof($copyList);$z++)
          $newPlayer[ $copyList[$z] ] = $player[ $copyList[$z] ];

        $teams[$x]["players"][$y] = $newPlayer;
      }
    }

    return $teams;
  }

/*******************************************************************************
 * Driver code goes here
 ******************************************************************************/
  // echo '<link rel="stylesheet" href="/omegaball/res/master.css">';

  $conn = connectDB("omegaball");
  $ajaxQuery = json_decode( $_GET["q"] );

  // Load
  $teams = [];

  $teams[] = loadTeam( $_GET["team1"] );
  $teams[] = loadTeam( $_GET["team2"] );

  $ballsOnGround = 5;

  // Start the output object
  $outputObj = [];
  $outputObj["teams"] = ($teams);
  // Game is an array
  $outputObj["game"] = [];

  for($x = 0;$x < 30;$x++)
    if( runRound() == false )
      break;

  echo json_encode( $outputObj );
?>