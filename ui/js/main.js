if(document.getElementById("S_form")) {
    document.getElementById("S_form").addEventListener("submit", function(e) {
        e.preventDefault();

        var msg = document.getElementById("S_text").value;

        if(msg != "") {
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    // console.log(this.response);
                } else if(this.readyState == 4) {
                    alert("Une erreur est survenue...");
                }
            };

            xhr.open("POST", "../php/included_scripts/send_message.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("msg=" + msg);
        } else {

        }
        $("#messages").load("../php/included_scripts/show_message.php");
        document.getElementById("S_text").value = "";
        return false;
    });

    document.getElementById("S_text").addEventListener("keydown", function(e) {
        if(e.keyCode == 49) { // Touche == "&"
            e.preventDefault();
        }
    });
}