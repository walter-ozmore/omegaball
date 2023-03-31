<!DOCTYPE html>
<html>
  <head>
    <title>Suggestions</title>
    <?php require_once realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/head.php"; ?>
    <script>     
        function generateNames()
        {
            ajax("/omegaball/ajax/name-gen.php", function(){
            if(this.readyState != 4 || this.status != 200) return;
            let text = this.responseText;
            let obj = JSON.parse(text);
            console.log(obj);
            let gen = document.getElementById("generate");
            gen.innerHTML = "";
            
            for(let name of obj)
            {
                gen.appendChild(mkEle("p", name));
            }

        })
        }

        function submitName()
        {
            let first = document.getElementById("first");
            let last = document.getElementById("last");
            let pos = -1;
            let name = document.getElementById("name").value;
            console.log(name);

            if (first.checked && last.checked) {
                console.log("This is both a first and last name");
                pos = -1;
            } 
            else if(last.checked){
                console.log("This is a last name");
                pos = 3;
            }
            else if(first.checked){
                console.log("This is a first name");
                pos = 1;
            }
            else{
                console.log("Please select if the name is first, last, or both");
                let error = document.getElementById("error");
                error.innerHTML = "PLEASE SELECT FIRST, LAST, OR BOTH.";
                error.style.display = "block";
            }
            
            

            let args ="pos="+pos+"&name="+name;

            ajax("/omegaball/ajax/add-name.php", function(){
                if(this.readyState != 4 || this.status != 200) return;
                let text = this.responseText;
                console.log(text);
            }, args);
        }
    </script>
    <style>
        .box {
            width: 25em; /* set your desired width */
            margin: auto; /* set margin to auto */
            text-align: center;
            align-items: center;
            justify-content: center;
            }
            .errorMessage {
            color: red; /* sets the text color to red */
            }

.error {
  display: block; /* shows the error message if the input is invalid */
}
    </style>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/omegaball/res/header.php"; ?>
  </header>
  <body>
    <div class="box border">
        <div id="generate">
        </div>
        <button onclick="generateNames()">
            GENERATE NAMES
        </button>
    </div>
    <div class="box border">
        <br></br>
        <label for="name">NAME:</label>
        <input type="text" id="name" name="name">
        <br></br>
        <p>
        <input type="checkbox" name="first" id="first">
        <label for="first">FIRST NAME</label>
        </p>
        <p>
        <input type="checkbox" name="last" id="last">
        <label for="last">LAST NAME</label>
        </p>
        <p class="errorMessage" style="display: none;" id="error"></p>
        <button onclick="submitName()" pattern="[a-zA-Z]+">
            SUBMIT NAME
        </button>
    </div>
  </body>
</html>