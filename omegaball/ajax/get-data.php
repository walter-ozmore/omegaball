<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";

  $data = loadData();

  echo json_encode( $data );
?>