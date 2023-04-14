<?php
  $args = json_decode($_POST["q"], true);

  echo json_encode( $args );
?>