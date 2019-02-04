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

        xhr.open("POST", "../php/send_message.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("msg="+msg);
    } else {

    }
    $("#messages").load("../php/show_message.php");
    document.getElementById("S_text").value = "";
    return false;
});