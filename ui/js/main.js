document.getElementById("S_form").addEventListener("submit", function(e) {
    e.preventDefault();

    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        console.log(this);
    };

    return false;
});