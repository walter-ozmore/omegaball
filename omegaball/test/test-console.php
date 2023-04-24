<!DOCTYPE html>
<html>
  <head>
    <title>Console</title>
    <?php
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php";
    ?>

    <script>
      class Terminal {
        constructor() {
          this.commands = {
            "clear": function(terminal) {
              terminal.output.innerHTML = "";
              terminal.ready();
            },
            "loadData": function(terminal) {
              terminal.print("Loading data...");
              ajax("/omegaball/ajax/get-data.php", function() {
                if (this.readyState != 4 || this.status != 200) return;

                let data = JSON.parse(this.responseText);
                console.log(data);

                terminal.print( build(data) );
                terminal.ready();
              });
            }
          };
        }

        build(object) {
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

          return mkEle("p", str);
        }

        ready() {
          this.scrollToInput();
          this.inputDiv.style.display = "block";
        }

        scrollToInput() {
          this.inputDiv.scrollIntoView();
        }

        print(message) {
          if( message instanceof HTMLDocument ) {
            this.output.appendChild( message );
            return;
          }

          this.output.appendChild( mkEle("p", message) );
        }

        createTerminal() {
          let terminal = this;
          let terminalEle = mkEle("div");
          terminalEle.classList.add("console");

          let output = mkEle("div");
          output.appendChild( mkEle("p", "Omegaball Terminal [Version 0.1]") );
          terminalEle.appendChild( output );
          this.output = output;

          terminal.inputDiv = mkEle("div");

          let dir = "Soul327@omegaball:/$";
          let dirName = mkEle("p", dir);
          dirName.style.display = "inline";
          dirName.style.marginRight = ".5em";
          terminal.inputDiv.appendChild(dirName);

          let inputEle = mkEle("input");
          inputEle.type = "text";
          inputEle.style.display = "inline";
          inputEle.classList.add("consoleInput");
          inputEle.addEventListener('keydown', function(e) {
            if (e.key !== 'Enter') return;
            terminal.inputDiv.style.display = "none";

            const command = this.value;
            this.value = "";
            output.appendChild( mkEle("p", `${dir} ${command}`) );

            for(let key in terminal.commands) {
              if( command == key ) {
                terminal.commands[key](terminal);
                return;
              }
            }

            output.appendChild( mkEle("p", `ERROR: No command found "${command}"`) );
            terminal.ready();
          });
          terminal.inputDiv.appendChild( inputEle );

          terminalEle.appendChild( terminal.inputDiv );

          return terminalEle;
        }
      }

      onWindowLoad(function(){
        let terminal = new Terminal();

        document.body.appendChild( terminal.createTerminal() );
      });
    </script>

    <style>
      .console {
        background-color: black;
        color: white;
        height: 12em;
      }

      .consoleInput {
        background-color: rgba(0, 0, 0, 0);
        color: white;
        border: none;
        outline: none;
      }
    </style>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>

  <body></body>
</html>