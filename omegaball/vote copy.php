<!DOCTYPE html>
<html>
  <head>
    <title>Vote</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>
    <style>
      h3 {
        margin-bottom: 0px;
      }

      .grid-container {
        display: grid;
        grid-auto-flow: column;
        grid-template-columns: 1fr 3fr;
      }

      .grid-container div {
        padding: .5em;
      }

      .grid-holder {
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: .5fr 2fr 1fr .5fr;
        grid-auto-flow: column;
        justify-content: space-evenly;
        display: grid;
      }

      .vote-image {
        max-width: 100%;
        width: 100%;
      }
    </style>
  </head>

  <header>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php";
    ?>
  </header>

  <body>
  <div class="grid-container">
      <!-- Directory -->
      <div id="directory" class="border">
      <p>  
        EDICTS
      </p>
      <p>  
        REWARDS
      </p>
      </div>

      <!-- Vote Info -->
      <div id="vote" class="border">
        <center>
          EDICTS
          <p style = "color: gray">
          Influence the Rules of Play.
          </p>
        <div class="grid-holder">
          <div> <p>SINKING SHIP</p> </div>
          <div><img class="vote-image" src="https://harpersbazaar.com.au/wp-content/uploads/2022/11/jack-titanic.jpg" alt="Sinking Ship"></div> 
          <div><p>The team with the fewest wins at the end of each season will be eliminated and replaced.</p></div>
          <div><button>VOTE</button></div>

          <div> <p>FORBIDDEN KNOWLEDGE</p> </div>
          <div><img class="vote-image" src="https://external-preview.redd.it/NQKtqcpXKQYcIRmOg3CJkUHkMvTnppv1hyw6sgDfldQ.jpg?auto=webp&s=724507cbe8c31b90731978f55d275968aad47048" alt="Sinking Ship"></div>
          <div><p>Forbidden.</p></div>
          <div><button>VOTE</button></div>
          
          <div><p>SCOREBOARD</p></div>
          <div><img class="vote-image" src="https://ih1.redbubble.net/image.179407712.0276/st,small,507x507-pad,600x600,f8f8f8.u4.jpg" alt="Sinking Ship"></div>
          <div><p>Games will be decided based on a point-scoring system.</p></div>
          <div><button>VOTE</button></div>
        </div>
        <button>
            PURCHASE VOTES
          </button>
          </center>
      </div>
    </div>
  </body>
</html>