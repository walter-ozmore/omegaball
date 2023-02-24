<?php
  function getPlayerByID($playerId) {
    return getPlayerFromId($playerId);
  }

  /**
   * Return the player array that is associated with the given ID
   */
  function getPlayerFromId($playerId) {
    global $teams;

    // If playerId is not numeric the return it back
    if( !is_numeric($playerId) )
      return $playerId;

    for($x = 0;$x < sizeof($teams);$x++) {
      $team = $teams[$x];
      $players = $teams[$x]["players"];

      for($y = 0;$y < sizeof($players);$y++) {
        $player = $players[$y];
        if($player["playerID"] == $playerId)
          return $player;
      }
    }
  }

  /**
   * Returns the team array from the given ID
   */
  function getTeamFromPlayerId($playerId) {
    global $teams;

    // If playerId is not numeric the return it back
    if( !is_numeric($playerId) )
      $playerId = $playerId["playerID"];

    for($x = 0;$x < sizeof($teams);$x++) {
      $team = $teams[$x];
      $players = $teams[$x]["players"];

      for($y = 0;$y < sizeof($players);$y++) {
        $player = $players[$y];
        if($player["playerID"] == $playerId)
          return $team;
      }
    }
  }


  /**
   * Saves the player back to the master data array
   */
  function savePlayer($player) {
    global $teams;
    $playerId = $player["playerID"];

    for($x = 0;$x < sizeof($teams);$x++) {
      $team = $teams[$x];
      $players = $teams[$x]["players"];

      for($y = 0;$y < sizeof($players);$y++) {
        if($players[$y]["playerID"] == $playerId) {
          $teams[$x]["players"][$y] = $player;
          return;
        }
      }
    }
  }

  function getMessage($key, $args) {
    $file = realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/messages.json";
    $data = file_get_contents($file);
    $obj = json_decode($data);

    if( !isset($obj->$key) ) {
      $str = " ";
      foreach($args as $k => $value) {
        $str .= "$k: $value | ";
      }
      return $key . " || " . $str;
    }

    $options = $obj->$key;
    $str = $options[array_rand($options, 1)];

    // Find and replace
    foreach($args as $key => $value) {
      $str = str_replace("<".$key.">", $value, $str);
    }

    return $str;
  }

  function random() {
    return (float)rand() / (float)getrandmax();
  }


  /**
   * The function will make the player choose a target to aim at, the target
   * can not be the player given or on the player's team
   *
   * @param player The player that is targeting
   *
   * @return targetPlayer The player that is targeted
   */
  function pickTarget($player = null) {
    global $teams;

    $team = getTeamFromPlayerId($player);

    $possibleTargets = [];

    for($x = 0;$x < sizeof($teams);$x++) {
      $lTeam = $teams[$x];
      $lPlayers = $lTeam["players"];

      if($lTeam["teamID"] == $team["teamID"]) continue;

      for($y = 0;$y<sizeof($lPlayers);$y++) {
        $lPlayer = $lPlayers[$y];

        if($lPlayer["inGame"] == false) continue;
        if($lPlayer["playerID"] == $player["playerID"]) continue;

        $possibleTargets[] = $teams[$x]["players"][$y]["playerID"];
      }
    }

    $targetPlayerID = $possibleTargets[ 0 ];
    $targetPlayer = getPlayerByID($targetPlayerID);

    return $targetPlayer;
  }
?>