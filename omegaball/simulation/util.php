<?php
  /**
   * Fetches the JSON from the file messages.json and saves it to a global var
   * $messageJson
   */
  function loadMessages() {
    global $messagesJson;

    $file = realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/messages.json";
    $data = file_get_contents($file);
    $messagesJson = json_decode($data);
  }


  /**
   *
   */
  function getMessage($key, $args) {
    global $messagesJson;

    if( !isset($messagesJson->$key) ) {
      $str = " ";
      foreach($args as $k => $value) {
        $str .= "$k: $value | ";
      }
      return $key . " || " . $str;
    }

    $options = $messagesJson->$key;
    $str = $options[array_rand($options, 1)];

    // Find and replace
    foreach($args as $key => $value) {
      $str = str_replace("<".$key.">", $value, $str);
    }

    return $str;
  }

  function message($key, $args, $updateTeams=true, $appendToLastMessage=false) {
    global $outputObj, $debug;

    $message = getMessage($key, $args);
    if($debug) echo "<p>".$message."</p>";

    if($appendToLastMessage) {
      $timeSlice = end($outputObj["game"]);
      $timeSlice["message"] .= " ". $message;
    } else {
      // Create message
      $timeSlice = [];
      $timeSlice["message"] = $message;
    }

    if($updateTeams) {
      global $data;
      $reducedData = reduceForDisplay( $data );
      $timeSlice["teams"] = $reducedData["teams"];
      $timeSlice["data"] = ["ballsOnGround"=>$data["ballsOnGround"]];
    }

    // If appending, then just update the last message
    if($appendToLastMessage) {
      $outputObj["game"][ array_key_last($outputObj["game"]) ] = $timeSlice;
      return;
    }

    // Add timeslice to end of game
    $outputObj["game"][] = $timeSlice;
  }


  /**
   * Remove excess data to reduce package size
   */
  function reduceForDisplay($data) {
    // unset( $data["teams"] );
    foreach( $data["teams"] as $teamAcronym => $team ) {
      foreach( $data["teams"][$teamAcronym]["players"] as $playerName => $player ) {
        $includeList = ["inGame", "heldBalls"];
        $newPlayer = [];
        foreach( $includeList as $index )
          $newPlayer[$index] = $player[$index];

        $data["teams"][$teamAcronym]["players"][$playerName] = $newPlayer;
      }

      $includeList = ["players", "teamColor", "teamName"];
      $newTeam = [];
      foreach( $includeList as $index )
        if( isset($team[$index]) )
          $newTeam[$index] = $data["teams"][$teamAcronym][$index];

      $data["teams"][$teamAcronym] = $newTeam;
    }
    return $data;
  }
?>