$(document).ready(function () {
    $("#formBtn").click(
        function () {
            sendAjaxForm('ajax_form', 'wp-content/plugins/simple-tickets/php/controllers/AppealController.php');
            var modal = document.getElementById("appealModal");
            modal.style.display = "none";
            return false;
        }
    );
});

function sendAjaxForm (ajax_form, url) {
    var data = $("#" + ajax_form).serializeArray();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: () => {
            alert('Ваша заявка отправлена!');
        },
        dataType: 'html'
    });
}
