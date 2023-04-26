
<div class = "mobileSetup">
  <div class="sideBarMenu">
    <a href="#" onClick="sidebar_open()">☰</a>
  </div>
  <div class="title">
    <a href="/omegaball/league">
      <img src="/omegaball/res/graphics/logo.png" width=500px>
    </a>
    <sub><p class="sub">---------- [ Simulated DodgeBall ] ----------</p></sub>
  </div>
</div>

  <!-- <div id="sidebar" style="width:0px;">
    <a href="/omegaball/Live.php">LIVE GAMES</a><br>
    <a href="/omegaball/League">LEAGUE</a><br>
    <a href="/omegaball/Vote">VOTE</a><br>
    <a href="/omegaball/Rules">RULES</a><br>
    <a href="/omegaball/Vote">VOTE</a><br>
    <a href="#" onClick="sidebar_close()">CLOSE</a><br>
  </div> -->

<div id="links" class="links">
  <a href="/omegaball/live">LIVE</a>
  <a href="/omegaball/league">LEAGUE</a>
  <a href="/omegaball/rules">RULES</a>
  <a href="/omegaball/account">ACCOUNT</a>
  <?php
    function addLinks() {
      global $isAdmin, $isLoggedIn;

      if($isLoggedIn == false) return;
      echo '<a href="/omegaball/vote">VOTE</a>';
      echo '<a href="/omegaball/suggestions">SUGGESTIONS</a>';
      if($isAdmin)
        echo '<a href="/omegaball/console/index">CONSOLE</a>';
    }

    addLinks();
  ?>
  <!-- <a href="/omegaball/vote">VOTE</a>
  <a href="/omegaball/console/index">CONSOLE</a>
  <a href="/omegaball/suggestions">SUGGESTIONS</a> -->
</div>

<div style="position: absolute; right: 1em; top: 0em;text-align: right">
  <p name="cu-username"></p>
  <p name="cu-team"></p>
  <p name="cu-currency"></p>
  <p name="cu-votes"></p>
</div>