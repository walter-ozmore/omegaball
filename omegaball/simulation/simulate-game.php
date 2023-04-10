<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";
  require_once "util.php";
  require_once "actions.php";

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
    $actionQueue = [];

    // Add players to the action queue by speed
    $speedLimit = 1;

    // We set a maximum here to prevent infinite loops
    for($x=0;$x<1000;$x++) {
      $highestSpeedPlayerObj = null;

      // Loop though teams
      foreach( $data["teams"] as $teamIndex => $teamObj ) {

        // Loop though players
        foreach( $data["teams"][$teamIndex]["players"] as $playerIndex => $playerObj ) {
          if( $playerObj["hustle"] >= $speedLimit ) {
            continue;
          }

          if( $highestSpeedPlayerObj == null ) {
            $highestSpeedPlayerObj = $playerObj;
            continue;
          }

          if( $playerObj["hustle"] > $highestSpeedPlayerObj["hustle"] ) {
            $highestSpeedPlayerObj = $playerObj;
          }
        }
      }

      // If there is no players found that fit then we are done
      if(!isset($highestSpeedPlayerObj) || $highestSpeedPlayerObj == null) {
        break;
      }

      // Add player to the action queue then set the speed limit
      $actionQueue[] = $highestSpeedPlayerObj["playerName"];
      $speedLimit = $highestSpeedPlayerObj["hustle"];
    }

    return $actionQueue;
  }


  /**
   * Runs the game by preforming actions for each player until the game is
   * finished. The game is finished when $gameRunning is false.
   */
  function runGame($args=[]) {
    global $data, $outputObj, $gameRunning;
    $gameRunning = true;
    $maxTurns = 50;

    loadMessages();

    // echo var_dump($args);

    // Load data
    $data = loadData( ["teams"=>$args["teams"]] );

    // Remove extra teams
    foreach( $data["teams"] as $teamIndex => $team ) {
      $hit = false;

      // Check if the team is in the array
      foreach( $args["teams"] as $teamAcronym )
        if( compare($teamIndex, $teamAcronym) ) {
          $hit = true;
          break;
        }

      // Skip if hit is true, AKA if the team was found in the args
      if($hit) continue;
      unset( $data["teams"][$teamIndex] );
    }

    // Setup local varables
    addLocalVarables();

    // Check the given arguments
    foreach($args as $key => $value) {}

    $outputObj = [];
    // Game is an array
    $outputObj["game"] = [];
    $outputObj["teams"] = reduceForDisplay($data)["teams"];

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

    return $outputObj;
    // $args = ["Hello"=>"World"];
    // return json_encode($args);
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
    if( $data["ballsOnGround"] > 0  && ($playerObj["heldBalls"] == 0 || random() < .5)) {
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
   * Adds local varables that are needed to run the simulation on the global
   * data array
   */
  function addLocalVarables() {
    global $data;

    // Set balls on the ground of the arena
    $data["ballsOnGround"] = 1;

    foreach( $data["teams"] as $teamIndex => $team ) {
      foreach( $team["players"] as $playerIndex => $player ) {
        $data["teams"][$teamIndex]["players"][$playerIndex]["heldBalls"] = 0;
        $data["teams"][$teamIndex]["players"][$playerIndex]["inGame"] = true;
      }
      $data["ballsOnGround"] += sizeof($team["players"]) / 5;
    }
  }
?>