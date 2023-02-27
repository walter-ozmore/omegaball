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

    $teams = getTeam($player);

    // Create time slice data
    message("player.out", ["player"=>getPlayerDisplayName($player)], true);
  }

  function returnPlayer() {}

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
      case 0:// Attempt to dodge
        $data["ballsOnGround"]++;

        if( $attackDodge > $defendDodge) {
          // Defender is hit with the ball
          message( "player.dodge.failure",[
            "attacker"=>getPlayerDisplayName($attacker),
            "defender"=>getPlayerDisplayName($defender)
          ]);
          setPlayerOut($defender);
          break;
        }
        // Defender dodges the ball
        message( "player.dodge.success",[
          "attacker"=>getPlayerDisplayName($attacker),
          "defender"=>getPlayerDisplayName($defender)
        ]);
        break;


      case 1:// Attempt to catch
        if( $attackCatch > $defendCatch ) {
          message( "player.catch.failure",[
            "attacker"=>getPlayerDisplayName($attacker),
            "defender"=>getPlayerDisplayName($defender)
          ]);
          setPlayerOut($defender);
          $data["ballsOnGround"]++;
          break;
        }
        message( "player.catch.success",[
          "attacker"=>getPlayerDisplayName($attacker),
          "defender"=>getPlayerDisplayName($defender)
        ]);
        setPlayerOut($attacker);
        $defender["heldBalls"]++;
        save($defender);
        break;
    }
  }

  function pickupBall($player) {
    global $data;
    $player["heldBalls"] = $player["heldBalls"] + 1;
    $data["ballsOnGround"] = $data["ballsOnGround"] - 1;
    save( $player );

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
    return $options[$index];
  }


  function message($key, $args, $updateTeams=true) {
    global $outputObj, $debug;

    $message = getMessage($key, $args);
    if($debug)
      echo "<p>".$message."</p>";

    // Create message
    $timeSlice = [];
    $timeSlice["message"] = $message;
    if($updateTeams) {
      global $data;
      $timeSlice["teams"] = $data["teams"];
    }
    $outputObj["game"][] = $timeSlice;
  }
?>