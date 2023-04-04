class GameManager {
  constructor(element) {
    // this.selectedTeams = [];
    this.textDiv = mkEle("div");
    this.teamDisplay = mkEle("div");

    element.appendChild(this.textDiv);
    element.appendChild(this.teamDisplay);
  }

  runGame(args) {
    // let args = {};
    // args["teams"] = this.selectedTeams;

    ajaxJson (
      "/omegaball/ajax/run-simulation.php",
      decode(obj),
      "q="+JSON.stringify(args)
    );
  }

  decode(obj) {
    // Clear info
    this.textDiv.innerHTML = "";

    // Display teams
    displayTeams(obj.teams);

    let delayTime = document.getElementById("speed").value;
    for(let x=0;x<obj.game.length;x++) {
      if(delayTime > 10)
        setTimeout(
          function () {
            processTimeSlice( obj.game[x], (x == obj.game.length - 1) )
          },
          delayTime * x
        );
      else
        processTimeSlice( obj.game[x], (x == obj.game.length - 1) )
    }
  }

  processTimeSlice(timeSlice, end = false) {
    let message = document.createElement("p");
    message.innerHTML = timeSlice.message;

    this.textDiv.append(message);

    // Scroll to the bottom of the text input
    // window.scrollTo(0, document.body.scrollHeight);
    this.textDiv.scrollTop = this.textDiv.scrollHeight;

    // Update teams if needed
    if( Object.hasOwn(timeSlice, "teams") ) {
      document.getElementById("teams").innerHTML = "";
      displayTeams(timeSlice.teams);
    }

    // Show the control buttons again
    if(end) {
      document.getElementById("controller").style.display = "block";
    }
  }
}