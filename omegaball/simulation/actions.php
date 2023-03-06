<?php
  /**
   * Drops all the balls that the player is holding then sets the player to out,
   * then anounces in the chat that the player is out
   *
   * @param player The array of the player to set out
   */
  function setPlayerOut($player) {
    global $data;

    $player["inGame"] = false;
    $data["ballsOnGround"] += $player["heldBalls"];
    $player["heldBalls"] = 0;

    save($player);

    // Create time slice data
    message("player.out", ["player"=>getPlayerDisplayName($player)], true, true);
  }

  function playerOPHit($player) {
    $player["outPoints"] = $player["outPoints"] - 1;
    if($player["outPoints"] <= 0)
      setPlayerOut($player);
    else
      save($player);
  }

  function playerHit($player) {
    global $data;

    if($data["rules"]["useOutPoints"]) {
      playerOPHit($player);
      return;
    }

    setPlayerOut($player);
    $player["outPoints"] = $data["rules"]["defaultOutPointsAmount"];
    save($player);
  }


  /**
   * The given player returns a team member
   */
  function returnPlayer($defender) {
    // Bring someone back
    $options = [];
    $team = getTeam($defender);
    // Queue up options
    foreach($team["players"] as $player) {
      if($player["inGame"]) continue;
      $options[] = $player;
    }

    if(sizeof($options) > 0) {
      $returnPlayer = $options[rand(0, sizeof($options) - 1)];
      $returnPlayer["inGame"] = true;

      message( "player.in", ["player"=>getPlayerDisplayName($defender, false), "returnedPlayer"=>getPlayerDisplayName($returnPlayer, false)] );

      save( $returnPlayer );
    }
  }


  /**
   * The given attacker will target a player and attempt to throw the ball at
   * them this will also calculate the targeted player's reaction to the ball
   * that is thrown at them
   *
   * @param player The player that is throwing the ball
   */
  function throwBall($attacker) {
    // Pick a target
    $targetPlayer = pickTarget($attacker);
    if($targetPlayer == null) {
      error("ERROR: No target for throw");
      return;
    }

    // Remove the ball from the attacker
    $attacker["heldBalls"] = $attacker["heldBalls"] - 1;
    save($attacker);

    // Calculate the roll
    $attackDodge = $attacker["foresight"] * random();
    $attackCatch = $attacker["tendons"] * random();

    // Create message
    message( "player.throw", ["attacker"=>getPlayerDisplayName($attacker), "defender"=>getPlayerDisplayName($targetPlayer)] );

    // Print out message

    // Check for ricochet

    // Defending player respond
    ballComing($targetPlayer, $attackDodge, $attackCatch, $attacker);
  }


  /**
   * The ball from $attacker is being thrown at $defender
   */
  function ballComing($defender, $attackDodge, $attackCatch, $attacker = null) {
    global $data;

    // Calculate defender's stats
    $defendDodge = $defender["celerity"] * random();
    $defendCatch = $defender["authority"] * random();

    // Check if the defender is smart enough to choose action
    $action = rand(0, 1);
    // if(  random() < $defender["rationale"])
      // Select the option with the best success

    switch($action) {
      /**
       * Attempt to dodge
       */
      case 0:
        // Add the ball back to the ground
        $data["ballsOnGround"]++;

        if( $attackDodge > $defendDodge) {
          // Defender is hit with the ball
          message( "player.dodge.failure",[
            "attacker"=>getPlayerDisplayName($attacker),
            "defender"=>getPlayerDisplayName($defender)
          ]);
          playerHit($defender);
          break;
        }
        // Defender dodges the ball
        message( "player.dodge.success",[
          "attacker"=>getPlayerDisplayName($attacker),
          "defender"=>getPlayerDisplayName($defender)
        ]);
        break;


      /**
       * Attempt to catch
       */
      case 1:
        if( $attackCatch > $defendCatch ) {
          message( "player.catch.failure",[
            "attacker"=>getPlayerDisplayName($attacker),
            "defender"=>getPlayerDisplayName($defender)
          ]);
          playerHit($defender);
          $data["ballsOnGround"]++;
          break;
        }
        message( "player.catch.success",[
          "attacker"=>getPlayerDisplayName($attacker),
          "defender"=>getPlayerDisplayName($defender)
        ]);
        playerHit($attacker);
        returnPlayer($defender);

        $defender["heldBalls"]++;
        save($defender);
        break;
    }
  }


  /**
   * Make the given player pick up a ball by remove it from the ground and
   * adding one to $player["heldBalls"]
   */
  function pickupBall($player) {
    global $data;
    $player["heldBalls"] = $player["heldBalls"] + 1;
    $data["ballsOnGround"] = $data["ballsOnGround"] - 1;
    save( $player );

    if( $data["rules"]["displayPickupMessages"] )
      message( "player.pickUpBall", ["player"=>getPlayerDisplayName($player, false)] );
  }


  function error($str) {
    global $debug;

    if($debug)
      echo "<p><span style='color: red'>$str</span></p>";
  }


  /**
   * The function will make the player choose a target to aim at, the target
   * can not be the player given or on the player's team
   *
   * @param attacker The player that is targeting
   *
   * @return targetPlayer The player that is targeted
   */
  function pickTarget($attacker = null) {
    global $data;
    // Lets build options
    $options = [];
    foreach( $data["teams"] as $teamIndex => $team ) {
      if( compare($teamIndex, $attacker["team"]) )
        continue;

      foreach( $team["players"] as $playerIndex => $player ) {
        if( $player["inGame"] ) {
          $options[] = $player;
        }
      }
    }

    if( sizeof($options) <= 0 ) return null;

    $index = rand(0, sizeof($options)-1);
    $target = $options[$index];

    // Reduce chance of being targeted
    $rand = random();
    if( sizeof($options) > 1 && $rand < $target["incorporeality"] ) {
      // message("player.changeTarget.incorporeality", [
      //   "attacker"=>getPlayerDisplayName($attacker),
      //   "defender"=>getPlayerDisplayName($target),
      //   "chance"=>$target["incorporeality"],
      //   "value"=>$rand
      // ], false);
      return pickTarget($attacker);
    }
    return $target;
  }
?>