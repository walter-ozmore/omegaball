<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/util.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/actions.php";

  /**
   * Check if the game is over by any means
   *
   * @return boolean True if the game is over
   */
  function isGameDone() {
    global $data, $outputObj;

    $teamsStillInGame = [];

    // Check if there is only one team left
    foreach( $data["teams"] as $teamObj ) {
      $playersInGame = 0;

      // for($y = 0;$y < sizeof($players);$y++) {
      foreach( $teamObj["players"] as $playerIndex => $playerObj ) {
        if($playerObj["inGame"])
          $playersInGame += 1;
      }

      if($playersInGame > 0)
        $teamsStillInGame[] = $teamObj;
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
   * Create the action queue or the order actions, these are ordered by the
   * players hustle. The actionQueue contains the player's names
   *
   * @return array actionQueue
   */
  function createActionQueue() {
    global $data;
    $verbos = false;
    $actionQueue = [];

    // Add players to the action queue by speed
    $speedLimit = 1;

    // We set a maximum here to prevent infinite loops
    for($x=0;$x<1000;$x++) {
      if($verbos) echo "Loop Start<br>";
      $highestSpeedPlayerObj = null;

      // Loop though teams
      foreach( $data["teams"] as $teamIndex => $teamObj ) {
        if($verbos) echo "$teamIndex<br>";

        // Loop though players
        foreach( $data["teams"][$teamIndex]["players"] as $playerIndex => $playerObj ) {
          if( $playerObj["hustle"] >= $speedLimit ) {
            if($verbos) echo " skipping due to speed limit<br>";
            continue;
          }

          if($verbos) echo $playerIndex;
          if( $highestSpeedPlayerObj == null ) {
            $highestSpeedPlayerObj = $playerObj;
            if($verbos) echo " set as default<br>";
            continue;
          }



          if( $playerObj["hustle"] > $highestSpeedPlayerObj["hustle"] ) {
            $highestSpeedPlayerObj = $playerObj;
            if($verbos) echo " set as fastest<br>";
          }
        }
      }

      // If there is no players found that fit then we are done
      if(!isset($highestSpeedPlayerObj) || $highestSpeedPlayerObj == null) {
        if($verbos) echo "Job Done<br>";
        break;
      }

      // Add player to the action queue then set the speed limit
      $actionQueue[] = $highestSpeedPlayerObj["playerName"];
      $speedLimit = $highestSpeedPlayerObj["hustle"];

      if($verbos) echo $highestSpeedPlayerObj["hustle"] . " " . $highestSpeedPlayerObj["playerName"] . "<br>";
    }

    return $actionQueue;
  }


  /**
   * Runs the game by preforming actions for each player until the game is
   * finished. The game is finished when $gameRunning is false.
   */
  function runGame() {
    global $data, $gameRunning;
    $gameRunning = true;
    $maxTurns = 200;

    // Loop for a maximum of 30 turns, just incase of a infinite loop
    for($turnCounter = 0; $turnCounter < $maxTurns && $gameRunning; $turnCounter++) {
      // Create the order of 'turns'
      $actionQueue = createActionQueue();

      foreach( $actionQueue as $action ) {
        action($action);
        if($gameRunning == false)
          break;
      }
    }
  }


  /**
   * Performs an action for the current player in the game.
   *
   * @param action The action to perform, which is assumed to be a player ID.
   *
   * @return void
   */
  function action($action) {
    global $gameRunning, $data;

    // Check if the game is over before continuing
    if(isGameDone()) {
      $gameRunning = false;
      return;
    }

    // Assume only players will be in the queue for now, this should be
    // changed later
    $playerID = $action;
    $playerObj = getPlayer($playerID);

    if($playerObj["inGame"] == false ) return;

    // Player makes a choice

    // If a player has no balls and there are balls on the ground, pick one up
    if( $playerObj["heldBalls"] == 0 && $data["ballsOnGround"] > 0 ) {
      pickupBall($playerObj);
      return;
    }

    // If the player has a ball, throw it
    if( $playerObj["heldBalls"] > 0 ) {
      throwBall($playerObj);
      return;
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
   * Remove excess data to reduce package size
   */
  function reduceForDisplay($data) {
    return $data;
  }


  /**
   * Adds local varables that are needed to run the simulation on the global
   * data array
   */
  function addLocalVarables() {
    global $data;

    // Set balls on the ground of the arena
    $data["ballsOnGround"] = 35;

    foreach( $data["teams"] as $teamIndex => $team )
      foreach( $team["players"] as $playerIndex => $player ) {
        $data["teams"][$teamIndex]["players"][$playerIndex]["heldBalls"] = 0;
        $data["teams"][$teamIndex]["players"][$playerIndex]["inGame"] = true;
      }
  }

  /****************************************************************************
   * Driver code goes here
   ****************************************************************************/
  $debug = isset($_GET["debug"]);
  if($debug)
    echo '<link rel="stylesheet" href="/omegaball/res/master.css">';

  loadMessages();

  // Load teams in to our data
  $args = [];
  $args["teams"][] = "MINO";
  $args["teams"][] = "HEAV";
  $data = loadData($args);

  // Remove teams
  foreach( $data["teams"] as $teamIndex => $team ) {
    if( compare($teamIndex, "MINO") || compare($teamIndex, "HEAV") )
      continue;
    unset( $data["teams"][$teamIndex] );
  }

  unset($args);

  addLocalVarables();

  // Start the output object
  $outputObj = [];
  // Game is an array
  $outputObj["game"] = [];
  $outputObj["teams"] = $data["teams"];

  runGame();

  echo json_encode( $outputObj );
?>