<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";
  // require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/actions.php";

  function isGameDone() {
    return false;
  }

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

  function runGame() {
    global $data, $gameRunning;
    $gameRunning = true;
    $maxTurns = 2;

    // Loop for a maximum of 30 turns, just incase of a infinite loop
    for($turnCounter = 0; $turnCounter < $maxTurns && $gameRunning; $turnCounter++) {
      // Create the order of 'turns'
      $actionQueue = createActionQueue();

      foreach( $actionQueue as $action ) {
        action($action);
      }
    }
  }

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

    $displayName = getPlayerDisplayName( $playerObj );
    echo $playerObj["hustle"] . " $displayName is taking their turn!<br>";

    // Player makes a choice

    // If a player has no balls and there are balls on the ground, pick one up
    // if( $playerObj["heldBalls"] == 0 && $data["ballsOnGround"] > 0 ) {
    //   pickupBall($playerObj);
    //   return;
    // }

    // If the player has a ball, throw it
    // if( $playerObj["heldBalls"] > 0 ) {
    //   throwBall($playerObj);
    //   return;
    // }
  }

  /**
   * Remove excess data to reduce package size
   */
  function reduceForDisplay($data) {
    return $data;
  }

  /****************************************************************************
   * Driver code goes here
   ****************************************************************************/

  // Load teams in to our data
  $args = [];
  $args["teams"][] = "MINO";
  $args["teams"][] = "HEAV";
  $data = loadData($args);
  unset($args);

  // Set balls on the ground of the arena
  $data["ballsOnGround"] = 5;

  // createActionQueue();
  runGame();
?>