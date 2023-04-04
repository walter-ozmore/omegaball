<!DOCTYPE html>
<html>
  <head>
    <title>Cursed JS</title>
    <?php
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php";
    ?>

    <script>
      function checker(str) {
        let output = `${str}  =>  ${eval(str)}`;
        document.body.appendChild( mkEle("p", output) );
      }

      window.onload = function() {
        checker("[] == ![]");
        checker("NaN == NaN");
        checker("0 == false");
        checker("'' == false");
        checker("[] == false");
      }
    </script>
  </head>
  <body>

  </body>
</html>