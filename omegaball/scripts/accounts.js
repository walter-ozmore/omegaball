class Accounts {
  static currentUser = null;
  static accounts = {};

  static init() {
    Accounts.loadCurrentUser();
  }

  static loadCurrentUser(returnFunction=null) {
    ajax("/account/version-3/ajax/get-current-user", function() {
      if (this.readyState != 4 || this.status != 200) return;
      let uid = this.responseText;
      Accounts.loadAccount(uid, function(user) {
        Accounts.currentUser = user;
        if(returnFunction != null) {
          returnFunction(user);
        }

        // // Add new links
        // addNavLink("VOTE", "/omegaball/vote");
        // if(user.consoleAccess == 1)
        //   addNavLink("CONSOLE", "/omegaball/console/index");
        // addNavLink("SUGGESTIONS", "/omegaball/suggestions");

        // Update all current user elements
        let elements;

        elements = document.getElementsByName("cu-username");
        for (let i = 0; i < elements.length; i++) {
          let ele = elements[i];
          ele.innerHTML = user["username"];
          ele.style.display = "block";
        }

        elements = document.getElementsByName("cu-currency");
        for (let i = 0; i < elements.length; i++) {
          let ele = elements[i];
          ele.innerHTML = user["currency"]+"t";
          ele.style.display = "block";
        }

        // TODO make this load the team with the full name and color
        elements = document.getElementsByName("cu-team");
        for (let i = 0; i < elements.length; i++) {
          let ele = elements[i];
          ele.innerHTML = user["team"];
          ele.style.display = "block";
        }
      });
    });
  }


  /**
   * Loads an account from the server using either sync ajax or non-sync
   *
   * @param {num} uid
   * @param {bool} sync
   * @returns
   */
  static loadAccount(uid, returnFunction=null, forceUpdate = false) {
    let accounts = this.accounts;

    if( forceUpdate == false && accounts[uid] != null ) {
      if(returnFunction != null)
        returnFunction(accounts[uid]);
    }

    if(uid == -1) return;

    ajax("/omegaball/ajax/fetch-user.php", function() {
      if (this.readyState != 4 || this.status != 200) return;
      if(this.responseText.length <= 0) return;
      let user = JSON.parse( this.responseText );
      accounts[user["uid"]] = user;

      if(returnFunction != null)
        returnFunction(user);
    }, "uid="+uid);
    return;
  }

  static loadAccountReturn() {}

  static currentUserReturn() {
    if (this.readyState != 4 || this.status != 200) return;
    if(this.responseText.length <= 0) return;
    let currentUser = JSON.parse(this.responseText);

    Accounts.accounts[currentUser["uid"]] = currentUser;

    let elements;

    elements = document.getElementsByName("cu-username");
    for (let i = 0; i < elements.length; i++) {
      let ele = elements[i];
      ele.innerHTML = currentUser.username;
      ele.style.display = "block";
    }

    elements = document.getElementsByName("cu-currency");
    for (let i = 0; i < elements.length; i++) {
      let ele = elements[i];
      ele.innerHTML = currentUser.currency;
      ele.style.display = "block";
    }

    elements = document.getElementsByName("cu-team");
    for (let i = 0; i < elements.length; i++) {
      let ele = elements[i];
      ele.innerHTML = currentUser.team;
      ele.style.display = "block";
    }

    elements = document.getElementsByName("cu-votes");
    for (let i = 0; i < elements.length; i++) {
      let ele = elements[i];
      ele.innerHTML = currentUser.votes;
      ele.style.display = "block";
    }
  }
}

Accounts.init();