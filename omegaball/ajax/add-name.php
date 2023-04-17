<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  /**
   * Codes
   *
   * 0 - All Good
   * 1 - Invalid position
   * 2 - Name already in use
   * 3 - Profanity
   * 4 - Unknown user
   */

  $conn = connectDB("newOmegaball");

  // Checks if the user is logged in. If not, exit.
  $user = getCurrentUser();
  if($user == NULL){
    echo "4";
    exit();
  }


  // Read profanity words from a file and store them in an array.
  $profanity = [];
  $file = fopen(realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/profanity.txt", "r");
  if ($file) {
      while (($line = fgets($file)) !== false) {
          $profanity[] = $line;
      }
      fclose($file);
  }

  $length = count($profanity);


  // Check if the name contains any profanity.
  $name = $_POST["name"];
  $name = addslashes($name);
  for($i = 0; $i < $length; $i++){
    if(strcmp($name, $profanity[$i]) == 0){
        echo "3 " . $profanity[$i];
        exit();
    }
  }


  // Checks if the value of pos is numeric or not.
  $pos = $_POST["pos"];
  if(is_numeric($pos) == FALSE){
    echo "1";
    exit();
  }

  $query = "SELECT playerName FROM PlayerName WHERE playerName = \"$name\"";
  $result = runQuery($conn, $query);


  // Checks the number of rows in the result set and exits if it's greater than 0.
  $num_rows = mysqli_num_rows($result);
  if($num_rows > 0){
    echo "2";
    exit();
  }


  // Add the name to the pool
  $uid = $user["uid"];
  $query = "INSERT INTO PlayerName (playerName, position, author) VALUES (\"$name\", $pos, $uid)";
  $result = runQuery($conn, $query);
  echo "0\n";
?>