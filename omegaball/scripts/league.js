
/**
 * Draws the division section
 *
 * @todo Use global data object rather than the a seperate ajax request
 */
function drawDivision() {
  if (this.readyState != 4 || this.status != 200) return;
  let txt = this.responseText;
  var obj = JSON.parse(txt);

  let divisionEle = document.getElementById("division");

  for (let division in obj) {
    // Draw division section
    let header = document.createElement("h2");
    header.innerHTML = division.toUpperCase();
    divisionEle.appendChild( header );

    // Draw all the teams in this section
    let indentDiv = document.createElement("div");
    indentDiv.classList.add("indent");

    for(let team in obj[division]) {
      team = obj[division][team];

      // Draw team name
      let teamEle = document.createElement("a");

      teamEle.style.color = team["teamColor"];
      teamEle.style.cursor = "pointer";
      teamEle.innerHTML = team["teamName"].toUpperCase();
      teamEle.onclick = function() {
        selectTeam(this, team["acronym"]);
      };

      // This is needed as they need to be block style without extending
      // to the entire width of the parent element
      let outer = document.createElement("p");
      outer.appendChild(teamEle)
      indentDiv.appendChild( outer );
    }

    divisionEle.appendChild( indentDiv );
  }
}

function selectTeam(ele, teamIndex) {
  // Select one and unselect all of the others
  toggleHighlight( ele, document.getElementById("division") )
  selectedTeam = teamIndex;

  let team = data["teams"][selectedTeam];

  let teamEle = document.getElementById("team");

  // Clear element
  teamEle.innerHTML = "";

  // Display team name
  let header = document.createElement("h2");
  header.innerHTML = team["teamName"].toUpperCase();
  header.style.color = team["teamColor"];
  teamEle.appendChild(header);

  // Display team stats
  let statsDiv = document.createElement("div");
  statsDiv.classList.add("indent");
  statsDiv.appendChild(mkEle("p", "RECORD: (0-0)"));
  statsDiv.appendChild(mkEle("p", "CHAMPIONSHIPS: 0"));
  statsDiv.appendChild(mkEle("p", "TIES: 0"));
  teamEle.appendChild(statsDiv);

  // Display players
  let playerHeader = document.createElement("h3");
  playerHeader.innerHTML = "PLAYERS";
  teamEle.appendChild(playerHeader);

  let playersDiv = document.createElement("div");
  playersDiv.classList.add("indent");
  playersDiv.id = "playerList";

  // Draw all players
  for(let playerName in team["players"]) {
    let player = team["players"][playerName];

    let wraper = document.createElement("p");

    let playerEle = document.createElement("span");
    playerEle.style.color = team["teamColor"];
    playerEle.style.cursor = "pointer";
    playerEle.onclick = function() {
      drawPlayer(this, teamIndex, playerName);
    };
    playerEle.innerHTML = player["playerName"];

    wraper.appendChild(playerEle);
    playersDiv.appendChild(wraper);
  }

  teamEle.appendChild(playersDiv);
}

function drawPlayer(ele, teamIndex, playerIndex) {
  // Select one and unselect all of the others
  toggleHighlight( ele, document.getElementById("playerList") );
  let player = data["teams"][teamIndex]["players"][playerIndex];
  let playerEle = document.getElementById("player");
  playerEle.innerHTML = "";

  let nameEle = document.createElement("h2");
  nameEle.style.color = data["teams"][teamIndex]["teamColor"];
  nameEle.innerHTML = playerIndex.toUpperCase();
  playerEle.appendChild( nameEle );

  let statsEle = document.createElement("div");
  statsEle.classList.add("indent");
  playerEle.appendChild(statsEle);

  let str, outOf;


  str = getStatString("POW: ", player, ["tendons", "animus", "cruelty"]);
  statsEle.appendChild(mkEle("p", str));

  str = getStatString("ACC: ", player, ["foresight", "grandeur", "markovianism", "cruelty"]);
  statsEle.appendChild(mkEle("p", str));

  str = getStatString("DEF: ", player, ["authority", "endurance", "mystique", "pluckiness"]);
  statsEle.appendChild(mkEle("p", str));

  str = getStatString("FIN: ", player, ["celerity", "hustle", "incorporeality", "poise"]);
  statsEle.appendChild(mkEle("p", str));

  str = getStatString("CHM: ", player, []);
  statsEle.appendChild(mkEle("p", str));

  str = getStatString("RAT: ", player, []);
  statsEle.appendChild(mkEle("p", str));


  outOf = 10;
  str = "ENTROPY: <span style='color: green'>";
  for(let x=1;x<=10;x++) {
    if( x > player["entropy"] * outOf )
      str += "</span>";
    str += "/";
  }
  playerEle.appendChild( mkEle("p", str) );
}

function getStatString(str, player, substats = []) {
  // Calculate average
  let amount = 0;
  let avg = 0;
  for(index in substats) {
    let substatName = substats[index];

    // If the stat isnt found, skip it
    if( !Object.hasOwn( player, substatName ) )
      continue;

    avg += parseFloat( player[ substatName ] );
    amount += 1;
    console.log(name);
  }

  if( amount == 0 ) return str + "NULL";

  avg = avg / amount;

  avg *= 5;
  // str += floor( avg );
  console.log(avg);

  for(let x=1;x<=avg;x++)
    str += "*";
  if(avg % 1 > .5)
    str += "~";

  return str;
}

function init() {
  ajax("/omegaball/ajax/get-data.php", function() {
    if (this.readyState != 4 || this.status != 200) return;
    data = JSON.parse(this.responseText);
  });
  ajax("/omegaball/ajax/get-teams.php", drawDivision);
}

var data = null;
var selectedTeam = null;
var selectedPlayer = null;

// Add document load event we use this method as window.onload is already
// used and can not be added to
document.addEventListener("DOMContentLoaded", function() {
  init();
});