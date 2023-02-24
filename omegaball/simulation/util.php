<?php
  /**
   * Fetches the JSON from the file messages.json and saves it to a global var
   * $messageJson
   */
  function loadMessages() {
    global $messagesJson;

    $file = realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/simulation/messages.json";
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
?>