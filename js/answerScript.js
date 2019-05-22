var modal = document.getElementById("notificationModal");

var ref = document.getElementById("notificationRef");

var span = document.getElementsByClassName("close")[0];

ref.onclick = function () {
    modal.style.display = "block";
}

span.onclick = function () {
    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}