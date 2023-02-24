<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/actions.php";

  /**
   * Creates the display name of the player given
   *
   * @param player
   * @param noExtra prevent extra information from being shown, such as when a
   * player is out of the game they are crossed out
   */
  function getDisplayPlayerName($playerObj, $noExtra=false) {
    $teamObj = getTeam( ["playerIndex"=>$playerObj["playerName"]] );

    $playerName = $playerObj["playerName"];
    $teamColor = $teamObj["teamColor"];
    $extra = ($playerObj["inGame"] || $noExtra)? "" : "text-decoration: line-through white;";
    $displayName = "<span style='color: $teamColor;$extra'>$playerName</span>";

    return $displayName;
  }


  function getPlayer($playerID) {
    global $data;

    foreach( $data["teams"] as $team ) {
      foreach( $team["players"] as $playerIndex => $player ) {
        if( is_string( $playerIndex ) ) {
          // String
          if( strcmp($playerIndex,  $playerID) == 0)
            return $player;
        } else {
          // Number
          if( $playerIndex == $playerID )
            return $player;
        }
      }
    }
  }

  function isGameDone() {
    return false;
  }

  function createActionQueue() {
    global $data;
    $verbos = false;
    $actionQueue = [];

    // Add players to the action queue by speed
    $speedLimit = 1;
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

    // Loop for a maximum of 30 turns, just incase of a infinite loop
    for($turnCounter = 0; $turnCounter < 10 && $gameRunning; $turnCounter++) {
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
    if(isGameDone()) return;

    // Assume only players will be in the queue for now, this should be
    // changed later
    $playerID = $action;
    $playerObj = getPlayer($playerID);

    echo $playerObj["hustle"] . " " . $playerObj["playerName"] ." is taking their turn!<br>";

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
   * Driver code goes here                                                    *
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