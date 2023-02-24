<?php
  /**
   * Drops all the balls that the player is holding then sets the player to out,
   * then anounces in the chat that the player is out
   *
   * @param playerId The ID of the player to set out
   */
  function setPlayerOut($player) {
    global $teams, $ballsOnGround, $outputObj;
    if( !isset($player) || $player == null) return;
    $player = getPlayerFromId( $player );
    $ballsOnGround += $player["heldBalls"];
    $player["heldBalls"] = 0;
    $player["inGame"] = false;
    savePlayer( $player );

    $displayName = getDisplayName($player, true);

    // Create time slice data
    $timeSlice = [];
    $timeSlice["message"] = getMessage("player.out", ["player"=>$displayName]);
    $timeSlice["data"] = $teams;

    $outputObj["game"][] = $timeSlice;
  }


  /**
   * Returns a player of the given player
   */
  function returnPlayer($player) {
    global $teams, $outputObj;

    $player = getPlayerFromId($player);
    $team = getTeamFromPlayerId( $player );

    $displayName = getDisplayName($player);

    // Check if there is a player out on the team
    $isPlayerOut = false;
    for($x = 0; $x < sizeof($team["players"]); $x++) {
      if( $team["players"][$x]["inGame"] == false ) {
        $isPlayerOut = true;
        break;
      }
    }

    if(!$isPlayerOut) {
      // There is no player to return
      // $outputObj["game"][]["message"] = getMessage("player.return.fail", ["player"=>$displayName]);
      return;
    }

    // Pick random player on the team that is out and not the player
    do {
      $rand = rand(0, sizeof($team["players"]) - 1);
      $targetPlayer = $team["players"][ $rand ];
    } while( $targetPlayer == $player && $targetPlayer["inGame"] == false );

    $targetPlayer["inGame"] = true;
    savePlayer($targetPlayer);
    $targetPlayerDisplayName = getDisplayName($targetPlayer);

    // Create time slice data
    $timeSlice = [];
    $timeSlice["message"] = getMessage("player.return", ["player"=>$displayName, "target"=>$targetPlayerDisplayName]);
    $timeSlice["data"] = $teams;

    $outputObj["game"][] = $timeSlice;
  }


  /**
   * The given player will target a player and attempt to throw the ball at them
   * this will also calculate the targeted player's reaction to the ball that is
   * thrown at them
   *
   * @param player The player that is throwing the ball
   */
  function throwBall($player, $ricochet = false) {
    global $ballsOnGround, $outputObj;

    // Get attach stats
    $attackDodge = $player["foresight"] * random();
    $attackCatch = $player["tendons"] * random();

    $displayName = getDisplayName($player);
    $targetPlayer = pickTarget($player);
    if($targetPlayer == null) {
      error("ERROR: No target for throw");
      return;
    }
    if( !$ricochet ) {
      // Remove a ball from their inventory
      if($player["heldBalls"] <= 0) return;
      $player["heldBalls"] = $player["heldBalls"] - 1;
      $ballsOnGround += 1;
      savePlayer($player);
    }
    $targetPlayerDisplayName = getDisplayName( $targetPlayer["playerID"] );

    // Send inital message
    if( $ricochet )
      $outputObj["game"][]["message"] = getMessage("player.throw.ricochet", ["attacker"=>$displayName, "defender"=>$targetPlayerDisplayName]);
    else
      $outputObj["game"][]["message"] = getMessage("player.throw", ["attacker"=>$displayName, "defender"=>$targetPlayerDisplayName]);


    /************************************************************************
     * Respond                                                              *
     ************************************************************************/
    // Calculate defender's stats
    $defendDodge = $targetPlayer["celerity"] * random();
    $defendCatch = $targetPlayer["authority"] * random();

    // Just pick a random one by default
    $dodge = rand(0, 1);

    // Check if smart enough
    if( random() < $player["rationale"] ) {
      // Smart enough
      if( $defendDodge > $attackDodge ) $dodge = true;
      if( $defendCatch > $attackCatch ) $dodge = false;
    }

    switch($action) {
      case 0:// Attempt to Dodge
        if( $attackDodge > $defendDodge ) {
          // Success, hit
          $outputObj["game"][]["message"] = getMessage("player.dodge.failure", ["attacker"=>$displayName, "defender"=>$targetPlayerDisplayName]);

          // Attempt to bounce
          // if(random() < .5) {
          //   throwBall($player, true);
          // }

          setPlayerOut( $targetPlayer );
          return;
        } else {
          // Failure, miss
          $outputObj["game"][]["message"] = getMessage("player.dodge.success", ["attacker"=>$displayName, "defender"=>$targetPlayerDisplayName]);
          return;
        }
        break;

      case 1://Attempt to Catch
        if( $attackCatch > $defendCatch ) {
          // Success, hit
          $outputObj["game"][]["message"] = getMessage("player.catch.failure", ["attacker"=>$displayName, "defender"=>$targetPlayerDisplayName]);
          setPlayerOut( $targetPlayer );
          return;
        } else {
          // Failure, caught
          $outputObj["game"][]["message"] = getMessage("player.catch.success", ["attacker"=>$displayName, "defender"=>$targetPlayerDisplayName]);
          setPlayerOut( $player );
          returnPlayer( $targetPlayer );
          return;
        }
        break;
    }
  }

  /**
   * Make the given player pickup a ball off the ground
   *
   * @param player The player that is picking up a ball
   */
  function pickupBall($player) {
    global $ballsOnGround, $outputObj, $teams;


    if($ballsOnGround <= 0) return;
    if($player["inGame"] == false) return;


    // Pick up a ball
    $player["heldBalls"] = 1;
    $ballsOnGround -= 1;
    savePlayer($player);


    // Write message to screen
    $displayName = getDisplayName($player);

    // Create time slice data
    $timeSlice = [];
    $timeSlice["message"] = getMessage("player.pickUpBall", ["player"=>$displayName] );
    $timeSlice["data"] = $teams;

    $outputObj["game"][] = $timeSlice;
  }


  function error($str) {
    global $outputObj;

    $outputObj["game"][]["message"] = "<span style='color:red;'>$str</span>";
  }

  function message($key, $args) {
    $outputObj["game"][]["message"] = getMessage($key, $args );
  }
?>