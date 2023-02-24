<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/lib.php";

  /**
   * This file will contains the various functions that are required for prizes
   */


  /**
   * Boosts a given stat
   */
  // function boost() {}


  /**
   * Maxes out a given stat
   */
  // function max() {}


  /**
   * Rerolls the given stat
   */
  // function reroll() {}


  /**
   * Moves a player from one team to another team
   */
  // function movePlayer() {}


  /**
   * Moves the given player to another team
   */
  // function changeTeams() {}

  function check($bool, $message="no message provided") {
    if($bool == false) {
      echo "<span color='red'>Error, $message</span>";
    }
  }

  function prize($number) {
    switch($number) {
      case 1:
        /**
         * Power Boost
         *
         * Players receive a 1-star boost to Power
         */

        $players = getPlayersFromTeam();
        for($players as $player) {

        }

        return;
      default:
        echo "No number given";
        return;
    }
  }



  /**
   * Runner code
   */
  check( (true == false) );
?>