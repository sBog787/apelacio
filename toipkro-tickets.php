<?php
/**
 * Plugin Name: TOIPKRO tickets
 * Plugin URI: https://github.com/sBog787/simple-tickets
 * Description: Plugin provides feedback functionality for visitors of site of educational institutions.
 * Version: 0.0.1
 * Author: sBog787
 */

/**
 * TODO:
 * 1) ПЕРЕИМЕНОВАТЬ ПЛАГИН & readme
 * 2) ajax
 * 3) отправка писем
 * 4) скрывать уведомления
 * 5) добавить текст соглашения
 *
 * баги:
 * 1) модалка выводится не для всех уведомлений
 * 2) не отображается кириллица
 * 3) подставлять значения из обращений в модалку с ответом
 */

include 'php/model/TicketService.php';
include 'php/views/helpers/NotificationsHelper.php';

register_activation_hook(__FILE__, 'createTicketsTable');

register_deactivation_hook(__FILE__, 'dropTicketsTable');

add_action('admin_notices', 'showNotifications');

add_action('admin_head', 'applyStyles');

add_action('wp_footer', 'showAppealForm');

add_action('wp_head', 'applyStyles');

function showNotifications(): void
{
    $tickets = ( new TicketService() )->findUncheckedTickets();
    if (! empty($tickets)) {
        $helper          = new NotificationsHelper($tickets);
        $pathToView      = __DIR__ . '/html/answer.html';
        $pathToScriptlet = __DIR__ . '/js/answerScript.js';
        if (file_exists($pathToView) && file_exists($pathToScriptlet)) {
            $html = file_get_contents($pathToView);
            $helper->viewNotificationList();
            $html .= '<script>';
            $html .= file_get_contents($pathToScriptlet);
            $html .= '</script>';
            echo $html;
        }
    }
}

function applyStyles(): void
{
    $pathToStyles = __DIR__ . '/css/style.css';
    $html         = '';
    if (file_exists($pathToStyles)) {
        $html .= '<style>';
        $html .= file_get_contents($pathToStyles);
        $html .= '</style>';
    }
    echo $html;
}

function showAppealForm(): void
{
    $pathToView      = __DIR__ . '/html/form.html';
    $pathToScriptlet = __DIR__ . '/js/formScript.js';
    $html            = '';
    if (file_exists($pathToView) && file_exists($pathToScriptlet)) {
        $html = file_get_contents($pathToView);
        $html .= '<script>';
        $html .= file_get_contents($pathToScriptlet);
        $html .= '</script>';
    }
    echo $html;
}

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
            secondName CHAR(255) NOT NULL,
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
