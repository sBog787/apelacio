<?php
/**
 * Plugin Name: Simple tickets
 * Plugin URI: https://github.com/sBog787/simple-tickets
 * Description: Plugin provides feedback functionality for visitors of your site.
 * Version: 0.0.1
 * Author: sBog787
 */

include 'php/TicketService.php';
include 'php/NotificationsHelper.php';

//add_action('admin_notices', 'viewNewTickets');

/**
 * Метод отображает уведомления о новых тикетах.
 * TODO РАЗОБРАТЬСЯ С ЭТИМ
 *
 * @return void
 */
function viewNewTickets(): void
{
    $tickets = ( new TicketService() )->findUncheckedTickets();
    if (! empty($tickets)) {
        foreach ($tickets as $ticket) {
            $fullName = createFullName($ticket['secondName'], $ticket['firstName'], $ticket['patronymic']);
            $html     =
                '<div id="message" class="notice notice-success is-dismissible">
                    <p>
                        Новое обращение от %FULLNAME%
                        <a id="notificationRef" href="#">Посмотреть</a>
                    </p>
                 </div>';
            $html     = preg_replace('/%FULLNAME%/', $fullName, $html);
            $html     .=
                '<div id="notificationModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div>
                            <h2>Обращение</h2>
                            <div class="form-item">
                                <h3>Тип обращения</h3>
                                <div>
                ' . $ticket['typeId'] . '
                                </div>
                            </div>
                            <div class="form-item">
                                <h3>Фамилия</h3>
                                <div>
                ' . $ticket['lastName'] . '
                                </div>
                            </div>
                            <div class="form-item">
                                <h3>Имя</h3>
                                <div>
                ' . $ticket['firstName'] . '
                                </div>
                            </div>
                            <div class="form-item">
                                <h3>Отчество</h3>
                                <div>
                ' . $ticket['patronymic'] . '
                                </div>
                            </div>
                            <div class="form-item">
                                <h3>E-mail</h3>
                                <div>
                                ' . $ticket['email'] . '
                                </div>
                            </div>
                            <div class="form-item">
                                <h3>Текст обращения</h3>
                                <div>
                                ' . $ticket['text'] . '
                                </div>
                            </div>
                            <form action="../wp-content/plugins/ticket/php/answer.php" method="post">
                                <div class="form-item">
                                    <h3>Текст ответа</h3>
                                    <textarea name="answer" maxlength="65535" class="form-field" required></textarea>
                                </div>
                                <div>
                                    <input type="submit" value="Отправить">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';

            $js   =
                '<script>
                    var modal = document.getElementById("notificationModal");

                    var ref = document.getElementById("notificationRef");

                    var span = document.getElementsByClassName("close")[0];

                    ref.onclick = function() {
                        modal.style.display = "block";
                    }

                    span.onclick = function() {
                        modal.style.display = "none";
                    }

                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                    </script>';
            $html .= $js;
            echo $html;
        }
    }
}

//add_action('admin_head', 'ticket_css');

//todo разобраться с этим
function ticket_css()
{
    echo "
	<style type='text/css'>
	.modal {
	  display: none; /* Hidden by default */
	  position: fixed; /* Stay in place */
	  z-index: 1; /* Sit on top */
	  padding-top: 100px; /* Location of the box */
	  left: 0;
	  top: 0;
	  width: 100%; /* Full width */
	  height: 100%; /* Full height */
	  overflow: auto; /* Enable scroll if needed */
	  background-color: rgb(0,0,0); /* Fallback color */
	  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}

	.modal-content {
	  background-color: #fefefe;
	  margin: auto;
	  padding: 20px;
	  border: 1px solid #888;
	  width: 50%;
	}

	.close {
	  color: #aaaaaa;
	  float: right;
	  font-size: 28px;
	  font-weight: bold;
	}

	.close:hover,
	.close:focus {
	  color: #000;
	  text-decoration: none;
	  cursor: pointer;
	}

	.form-item {
		margin-bottom: 10px;
	}

	input, label, select, textarea {
    	display:block;
	}

	.form-field {
		width: 100%;
	}

	#myBtn {
	position: fixed;
	right: 0px;
	bottom: 0px;
	}

	</style>
	";
}

//todo exception
/**
 * Метод выполняет роль контроллера, отображающего уведомления в админ-панеле.
 *
 * @return void
 */
function showNotifications(): void
{
    $tickets = ( new TicketService() )->findUncheckedTickets();
    if (! empty($tickets)) {
        $helper     = new NotificationsHelper($tickets);
        $pathToView = __DIR__ . '/php/views/appealAnswer.html';
        render($pathToView, $helper);
    }
}

add_action('admin_notices', 'showNotifications');

add_action('admin_head', 'ticket_css');

function showAppealForm(): void
{
    $pathToView = __DIR__ . '/php/views/appealForm.html';
    $html       = '';
    if (file_exists($pathToView)) {
        $html = file_get_contents($pathToView);
    }
    echo $html;
}

add_action('wp_footer', 'showAppealForm');

add_action('wp_head', 'ticket_css');

/**
 * Метод рендерит уведомления в админ-панеле.
 *
 * @param string              $pathToView Путь до представления.
 * @param NotificationsHelper $helper     Хелпер представления.
 *
 * @return void
 */
function render(string $pathToView, NotificationsHelper $helper): void
{
    if (file_exists($pathToView)) {
        $html = file_get_contents($pathToView);

        $helper->viewNotificationList();
        //todo сделать одно модальное окно для отображения всех тикетов
        echo $html;
    }
}

//register_activation_hook(__FILE__, 'createTicketsTable');

//register_deactivation_hook(__FILE__, 'dropTicketsTable');

function createTicketsTable()
{
    $mysqli = new mysqli('127.0.0.1', 'root', 123, 'wp');
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    $query =
        'CREATE TABLE IF NOT EXISTS wp_tickets (
            id INT PRIMARY KEY AUTO_INCREMENT,
            typeId TINYINT NOT NULL DEFAULT 0,
            lastName CHAR(255) NOT NULL,
            firstName CHAR(255) NOT NULL,
            patronymic CHAR(255) NOT NULL,
            email CHAR(255) NOT NULL,
            text TEXT NOT NULL,
            flagAgreed TINYINT NOT NULL,
            statusId TINYINT NOT NULL DEFAULT 0)';
    if (! $mysqli->query($query)) {
        echo 'Не удалось создать таблицу: ' . $mysqli->errno . ') ' . $mysqli->error;
    }
}

function dropTicketsTable()
{
    $mysqli = new mysqli('127.0.0.1', 'root', 123, 'wp');
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    $query =
        'DROP TABLE IF EXISTS wp_tickets';
    if (! $mysqli->query($query)) {
        echo 'Не удалось удалить таблицу: ' . $mysqli->errno . ') ' . $mysqli->error;
    }
}
