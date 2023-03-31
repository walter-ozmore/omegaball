<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secure/database.php";
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  $conn = connectDB("newOmegaball");

  $profanity = ["fuck", "shit", "bitch", "damn", "cunt", "ass", "whore", "slut"]
  $length = count($profanity)

  $name = $_GET["name"];
  $name = addslashes($name);
  for($i = 0; $i < $length; $i++){
    if(strcmp($name, $profanity[$i] == 0)){
        echo "3";
        exit();
    }
  }

  $pos = $_GET["pos"];
  if(is_numeric($pos) = FALSE){
    echo "1"
    exit();
  }
  
  $query = "SELECT playerName FROM PlayerName WHERE playerName = \"$name\"";
  $result = runQuery($conn, $query);

  $num_rows = mysqli_num_rows($result);
  echo $num_rows;
  if($num_rows > 0){
    echo "2";
    exit();
  }

  $query = "INSERT INTO PlayerName (playerName, position) VALUES (\"$name\", $pos)";
  
//   $result = runQuery($conn, $query);
  echo "0"
  
//   while ($row = $result->fetch_assoc()){
//     $firstName[] = $row["playerName"];
//   }
?>