var modal = document.getElementById("appealModal");

var btn = document.getElementById("appealButton");

var span = document.getElementsByClassName("close")[0];

var policyModal = document.getElementById('policyModal');

var policySpan = document.getElementsByClassName("policy-close")[0];

$(document).ready(function () {
    $('#policyRef').bind('click', function (e) {
        e.preventDefault();
        policyModal.style.display = "block";
    });
});

btn.onclick = function() {
    modal.style.display = "block";
};

span.onclick = function() {
    modal.style.display = "none";
};

policySpan.onclick = function() {
    policyModal.style.display = "none";
};

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};