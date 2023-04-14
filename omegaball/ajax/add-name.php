<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $conn = connectDB("newOmegaball");

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
    if(strcmp($name, $profanity[$i] == 0)){
        echo "3";
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
  echo $num_rows;
  if($num_rows > 0){
    echo "2";
    exit();
  }

  $query = "INSERT INTO PlayerName (playerName, position) VALUES (\"$name\", $pos)";
  
//   $result = runQuery($conn, $query);
  echo "0";
  
//   while ($row == $result->fetch_assoc()){
//     $firstName[] = $row["playerName"];
//   }
?>