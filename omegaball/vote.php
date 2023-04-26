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
        let noti = createNotification();
        let ele = mkEle("p", "SUBMIT VOTES:");
        noti.appendChild(ele);
        let line = mkEle("p", "ENTER THE NUMBER OF VOTES YOU WOULD <br>");
        line.style.display = "inline";
        noti.appendChild(line);
        let line2 = mkEle("p", "LIKE TO SUBMIT: <br>");
        line2.style.display = "inline";
        noti.appendChild(line2);
        let buy = mkEle("p", "(THESE VOTES WILL BE CAST FOR THE SELECTED EDICT)");
        buy.style.color = "gray";
        noti.appendChild(buy);

        let input = document.createElement("INPUT");
        input.setAttribute("type", "number");
        input.setAttribute("min", "1");
        input.style.display = "block";
        input.style.marginLeft = "auto";
        input.style.marginRight = "auto";
        input.style.marginBottom = "1em";
        noti.appendChild(input);

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

      function submitVote() {
        let voteNoti = createNotification();
        let voteSubmit = mkEle("p", "SUBMIT VOTES:");
        voteNoti.appendChild(voteSubmit);
        let line = mkEle("p", "ENTER THE NUMBER OF VOTES YOU WOULD <br>");
        line.style.display = "inline";
        voteNoti.appendChild(line);
        let line2 = mkEle("p", "LIKE TO SUBMIT: <br>");
        line2.style.display = "inline";
        voteNoti.appendChild(line2);

        let inp = document.createElement("INPUT");
        inp.setAttribute("type", "number");
        inp.setAttribute("min", "1");
        inp.style.display = "block";
        inp.style.marginLeft = "auto";
        inp.style.marginRight = "auto";
        inp.style.marginBottom = "1em";
        voteNoti.appendChild(inp);

        let can = mkEle("button", "CANCEL");
        can.style.display = "inline";
        can.style.marginRight = "5em";
        voteNoti.appendChild(can);
        can.onclick = function() {
          closeNotification(this);
        };

        let subButton = mkEle("button", "SUBMIT");
        subButton.style.display = "inline";
        voteNoti.appendChild(subButton);
      }
      // Get votes
      // The args for the get-votes query. Change each season.
      let args = {season: 0};

      ajaxJson("/omegaball/ajax/get-votes.php", function(obj){

        let outDiv = mkEle("div");
        outDiv.style.textAlign = "center";
        outDiv.classList.add("grid-holder");

        // Building grid
        for(let vote of obj){
          let id = vote["id"];
          let description = vote["description"];
          let image = vote["image"];
          let title = vote["title"];

          outDiv.appendChild( mkEle("div", title) );
          outDiv.appendChild( mkEle("div", "<img class='vote-image' src='"+title+"'>") );
          outDiv.appendChild( mkEle("div", description) );

          let voteDiv = mkEle("div");
          let voteButton = mkEle("button", "VOTE");
          voteButton.onclick = function() {
          submitVote();
          };
          voteDiv.appendChild(voteButton);
          outDiv.appendChild(voteDiv);
        }

        let purchaseDiv = mkEle("div");
        purchaseDiv.style.textAlign = "center";
        let purchaseButton = mkEle("button", "PURCHASE VOTES");
        purchaseButton.onclick = function() {
          purchase();
        };
        purchaseDiv.appendChild(purchaseButton);

        document.getElementById("vote").appendChild(outDiv);
        document.getElementById("vote").appendChild(purchaseDiv);
      }, args);

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
          <button class="selected">EDICTS</button>
        </p>
        <p>
          <button>REWARDS</button>
        </p>
      </div>
      <div id="vote" class="border">
        <p style="text-align: center">EDICTS</p>
        <p class="subtle text-center">Influence the Rules of Play.</p>
      </div>
    </div>
  </body>
</html>