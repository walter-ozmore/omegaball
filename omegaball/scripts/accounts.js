class Accounts {
  static accounts = {};

  static init() {
    Accounts.loadCurrentUser();
  }

  static loadCurrentUser(uid = null) {
    // Grab the current user id if it is found
    if(uid == null) {
      ajax("/account/version-3/ajax/get-current-user", function() {
        if (this.readyState != 4 || this.status != 200) return;
        Accounts.loadCurrentUser(this.responseText);
      });
      return;
    }

    if(uid != -1)
      ajax("/omegaball/ajax/fetch-user", Accounts.currentUserReturn, "uid="+uid);
  }

  static getAccount(uid) {
    if( this.accounts[uid] != null ) {
      return this.accounts[uid];
    }
    Accounts.loadAccount(uid);
    return this.accounts[uid];
  }


  /**
   * Loads an account from the server using either sync ajax or non-sync
   *
   * @param {num} uid
   * @param {bool} sync
   * @returns
   */
  static loadAccount(uid, sync=true) {
    console.log("Fetching UID: "+uid);
    if(sync) {
      let txt = syncAjax("/omegaball/ajax/fetch-user.php", "uid="+uid);
      let user = JSON.parse( txt );
      this.accounts[user["uid"]] = user;
      return user;
    }
    ajax("/omegaball/ajax/fetch-user.php", Accounts.loadAccountReturn, "uid="+uid);
    return;
  }

  static loadAccountReturn() {}

  static currentUserReturn() {
    if (this.readyState != 4 || this.status != 200) return;
    if(this.responseText.length <= 0) return;
    let currentUser = JSON.parse(this.responseText);

    Accounts.accounts[currentUser["uid"]] = currentUser;

    // Select all elements with the name "test"
    let elements = document.getElementsByName("cu-username");
    for (let i = 0; i < elements.length; i++) {
      elements[i].innerHTML = currentUser.username;;
    }
  }
}

Accounts.init();