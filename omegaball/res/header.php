<?php
  require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

  // Remove due to git
  // require_once realpath($_SERVER["DOCUMENT_ROOT"])."/account/newlib.php";
?>

<script>
  function sidebar_close() {
    document.getElementById("sidebar").style.width = "0";
    document.getElementById("mainContentBody").style.overflow = "scroll";
  }

  function sidebar_open() {
    document.getElementById("sidebar").style.width = "60%";
    document.getElementById("mainContentBody").style.overflow = "hidden";
  }
</script>

  <div class = "mobileSetup">
    <div class="sideBarMenu">
      <a href="#" onClick="sidebar_open()">☰</a>
    </div>
    <div class="title">
      <center>
        <a href="/omegaball/league">
          <img src="/omegaball/res/graphics/logo.png" width=500px>
        </a>
      </center>
    </div>
  </div>
  <center><sub><p class="sub">---------- [ Simulated DodgeBall ] ----------</p></sub></center>

  <!-- <div id="sidebar" style="width:0px;">
    <a href="/omegaball/Live.php">LIVE GAMES</a><br>
    <a href="/omegaball/League">LEAGUE</a><br>
    <a href="/omegaball/Store">STORE</a><br>
    <a href="/omegaball/Rules">RULES</a><br>
    <a href="/omegaball/Vote">VOTE</a><br>
    <a href="#" onClick="sidebar_close()">CLOSE</a><br>
  </div> -->

<div id="links" class="links">
  <a href="/omegaball/live">LIVE</a>
  <a href="/omegaball/league">LEAGUE</a>
  <a href="/omegaball/store">STORE</a>
  <a href="/omegaball/rules">RULES</a>
  <?php
    if(isset($currentUser) && $currentUser != null) {
      echo '<a href="/omegaball/account">ACCOUNT</a>';
    }
  ?>
</div>