<?php
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);

  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/version-3/lib.php";

  function checkUser() {
    global $isAdmin, $isLoggedIn;
    $isAdmin = false;

    $user = getCurrentUser();
    if($user == null) return;
    $uid = $user["uid"];

    $isLoggedIn = 1;
    $conn = connectDB("newOmegaball");
    $query = "SELECT isAdmin FROM User WHERE uid='$uid'";
    $result = runQuery($conn, $query);
    if($result->fetch_assoc()["isAdmin"]) {
      $isAdmin = true;
    }
  }

  function checkRestricted() {
    global $isAdmin;
    if($isAdmin) return;

    $url = substr( $_SERVER['REQUEST_URI'], 11 );

    $check = "console";
    if(substr( $url, 0, strlen($check) ) === $check) {
      header("Location: /omegaball/util/404");
      die();
    }

    $check = "test";
    if(substr( $url, 0, strlen($check) ) === $check) {
      header("Location: /omegaball/util/404");
      die();
    }
  }

  checkUser();
  checkRestricted();
?>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
<link rel="icon" type="image/png" href="/omegaball/res/graphics/favicon-temp.png">

<!-- Scripts -->
<script src="/omegaball/scripts/lib.js"></script>
<script src="/omegaball/scripts/accounts.js"></script>
<script src="/omegaball/scripts/notifications.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- Style sheets -->
<link rel="stylesheet" href="/omegaball/res/master.css">