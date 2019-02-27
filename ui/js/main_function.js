function do_song(file) {
    var validExtensions = ["mp3", "wav"];
    if(validExtensions.indexOf(get_file_extension(file))) {
        if(!document.getElementById("notifs")) {
            var notifs  = document.createElement("div");
            notifs.id = "notifs"; 
            document.body.appendChild(notifs);
        } else {
            var notifs = document.getElementById("notifs");
        }
        
        var audioName = file;
        var audioFileName = get_file_name(file);
        var audioSource = "../ui/songs/" + audioName;

        if(document.getElementById(audioFileName)) {
            var audio = notifs.getElementById(audioFileName);
        } else {
            var audio = document.createElement("audio");
            notifs.appendChild(audio);
        }
        audio.src = audioSource;
        // audio.autoplay;
        audio.play();

        // suppression au bout de 3 secondes :
        setTimeout(function (){
            notifs.removeChild(audio);
        }, 3000);
    } else {
        return false;
    }
}
function get_file_name(fileName) {
    if(typeof fileName == "string") {
        var fileName_i = fileName.toLowerCase();
        if(fileName_i.lastIndexOf(".") != -1)     {
            var fileName_i = fileName_i.slice(0, fileName_i.lastIndexOf("."));
            return fileName_i;
        } else {
            return false;
        }
    }
}
function get_file_extension(fileName) {
    if(typeof(fileName) == "string") {
        var fileName_i = fileName.toLowerCase();
        if(fileName_i.lastIndexOf(".") != -1)     {
            var fileName_i = fileName_i.slice(fileName_i.lastIndexOf(".") + 1);
            return fileName_i;
        } else {
            return false;
        }
    }
}

function get_clipboard() {
    return clipboardData.getData("Text");
}