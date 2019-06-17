$(document).ready(function () {
    $("#answerBtn").click(
        function () {
            alert(13213123);
            sendAjaxForm('ajax_answer', 'wp-content/plugins/apelacio/php/controllers/AnswerController.php');
            var modal = document.getElementById("notificationModal");
            modal.style.display = "none";
            return false;
        }
    );
});

function sendAjaxForm (ajax_answer, url) {
    // var data = $("#" + ajax_answer).serializeArray();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: () => {
            alert('Ваш ответ отправлен!');
        },
        dataType: 'html'
    });
}
