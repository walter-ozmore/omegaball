<!DOCTYPE html>
<html>
  <head>
    <title>Omegaball || Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

    <link rel="stylesheet" href="/omegaball/res/master.css">

    <!-- Scripts -->
    <script src="/omegaball/res/lib.js"></script>
    <script>
      function loadData() {
        if (this.readyState != 4 || this.status != 200) return;

        let data = JSON.parse(this.responseText);
        console.log(data);

        let windowEle = document.getElementById("data-viewer-window");
        windowEle.innerHTML = build(data);
      }

      function build(object) {
        let str = "";
        for (let key in object) {
          let value = object[key];
          let hasChild = ( typeof value === 'object' && !Array.isArray(value) && value !== null );

          if (hasChild) {
            str += "<p>"+ key +": </p><div class='indent'>"+build(object[key], )+"</div>";
          } else {
            str += "<p>"+ key +": "+ value +"</p>";
          }
        }

        return str;
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>

  <body>
    <div id="debug" class="border" style="padding: .5em;">
      <div id="data-viewer">
        <div id="data-viewer-window" style="max-height: 80vh; overflow:scroll;"></div>
        <button onclick='ajax("/omegaball/ajax/get-data.php", loadData);'>Load Data</button>
      </div>
    </div>
  </body>
</html>