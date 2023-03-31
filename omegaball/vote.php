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
        max-height: 40em;
      }

      .vote-image {
        max-width: 100%;
        width: 100%;
      }
    </style>
    <script>
      function purchase() {
        let temp = createNotification();
        let ele = mkEle("p", "PURCHASE VOTES:");
        temp.appendChild(ele);
        let line = mkEle("p", "ENTER THE NUMBER OF VOTES YOU WOULD <br>");
        line.style.display = "inline";
        temp.appendChild(line);
        let line2 = mkEle("p", "LIKE TO PURCHASE: <br>");
        line2.style.display = "inline";
        temp.appendChild(line2);
        let buy = mkEle("p", "(1 VOTE COSTS 10 COINS)");
        buy.style.color = "gray";
        temp.appendChild(buy);

        let x = document.createElement("INPUT");
        x.setAttribute("type", "number");
        x.setAttribute("min", "1");
        x.style.display = "block";
        x.style.marginLeft = "auto";
        x.style.marginRight = "auto";
        x.style.marginBottom = "1em";
        temp.appendChild(x);

        let can = mkEle("button", "CANCEL");
        can.style.display = "inline";
        can.style.marginRight = "5em";
        temp.appendChild(can);
        can.onclick = function() {
          closeNotification(this);
        };

        let cont = mkEle("button", "CONTINUE");
        cont.style.display = "inline";
        temp.appendChild(cont);
      }
      function submit() {
        let temp = createNotification();
        let ele = mkEle("p", "SUBMIT VOTES:");
        temp.appendChild(ele);
        let line = mkEle("p", "ENTER THE NUMBER OF VOTES YOU WOULD <br>");
        line.style.display = "inline";
        temp.appendChild(line);
        let line2 = mkEle("p", "LIKE TO SUBMIT: <br>");
        line2.style.display = "inline";
        temp.appendChild(line2);
        let buy = mkEle("p", "(THESE VOTES WILL BE CAST FOR THE SELECTED EDICT)");
        buy.style.color = "gray";
        temp.appendChild(buy);

        let x = document.createElement("INPUT");
        x.setAttribute("type", "number");
        x.setAttribute("min", "1");
        x.style.display = "block";
        x.style.marginLeft = "auto";
        x.style.marginRight = "auto";
        x.style.marginBottom = "1em";
        temp.appendChild(x);

        let can = mkEle("button", "CANCEL");
        can.style.display = "inline";
        can.style.marginRight = "5em";
        temp.appendChild(can);
        can.onclick = function() {
          closeNotification(this);
        };

        let cont = mkEle("button", "CONTINUE");
        cont.style.display = "inline";
        temp.appendChild(cont);
      }
    </script>
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
        <button class = "selected"> 
        EDICTS
        </button>
      </p>
      <p>
        <button>  
        REWARDS
        </button>
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
          <div><button onclick = "submit()">VOTE</button></div>

          <div> <p>FORBIDDEN KNOWLEDGE</p> </div>
          <div><img class="vote-image" src="https://external-preview.redd.it/NQKtqcpXKQYcIRmOg3CJkUHkMvTnppv1hyw6sgDfldQ.jpg?auto=webp&s=724507cbe8c31b90731978f55d275968aad47048" alt="Sinking Ship"></div>
          <div><p>Forbidden.</p></div>
          <div><button onclick = "submit()">VOTE</button></div>
          
          <div><p>SCOREBOARD</p></div>
          <div><img class="vote-image" src="https://ih1.redbubble.net/image.179407712.0276/st,small,507x507-pad,600x600,f8f8f8.u4.jpg" alt="Sinking Ship"></div>
          <div><p>Games will be decided based on a point-scoring system.</p></div>
          <div><button onclick = "submit()">VOTE</button></div>
        </div>
        <button onclick = "purchase()">
            PURCHASE VOTES
        </button>
          </center>
      </div>
    </div>
  </body>
</html>