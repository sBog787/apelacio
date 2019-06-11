<?php
/**
 * Plugin Name: Apelacio
 * Plugin URI: https://github.com/sBog787/apelacio
 * Description: Plugin provides feedback functionality for your website.
 * Version: 0.0.1
 * Author: sBog787
 */

require_once 'php/models/AppealService.php';
require_once 'php/views/helpers/NotificationsHelper.php';

register_activation_hook(__FILE__, 'activateApelacio');

register_deactivation_hook(__FILE__, 'deactivateApelacio');

add_action('wp_footer', 'showAppealForm');

add_action('wp_head', 'applyStyles');

add_action('wp_head', 'addJQuery');

add_action('wp_head', 'addAjax');

add_action('admin_notices', 'showNotifications');

add_action('admin_head', 'applyStyles');

add_action('admin_head', 'addJQuery');

function showNotifications(): void
{
    $appealList = ( new AppealService() )->findUncheckedAppeals();
    if (empty($appealList)) {
        return;
    }
    $helper          = new NotificationsHelper($appealList);
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

function showAppealForm(): void
{
    $pathToView       = __DIR__ . '/html/form.html';
    $pathToFormScript = __DIR__ . '/js/formScript.js';
    $pathToPolicy     = __DIR__ . '/html/policy.html';

    if (! file_exists($pathToView) || ! file_exists($pathToFormScript) || ! file_exists($pathToPolicy)) {
        return;
    }

    $html = file_get_contents($pathToView);
    $html .= file_get_contents($pathToPolicy);
    $html .= '<script>';
    $html .= file_get_contents($pathToFormScript);
    $html .= '</script>';

    echo $html;
}

function applyStyles(): void
{
    $pathToStyles = __DIR__ . '/css/style.css';

    if (! file_exists($pathToStyles)) {
        return;
    }

    $html = '<style>';
    $html .= file_get_contents($pathToStyles);
    $html .= '</style>';
    echo $html;
}

function addJQuery(): void
{
    echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js""></script>';
}

function addAjax(): void
{
    $pathToAjax = __DIR__ . '/js/ajax.js';

    if (! file_exists($pathToAjax)) {
        return;
    }

    $pathToAjax = 'wp-content/plugins/simple-tickets/js/ajax.js'; //todo переделать
    $html       = '<script src="';
    $html       .= $pathToAjax;
    $html       .= '"></script>';
    echo $html;
}

//todo логи
const PREFIX = 'apelacio_';

function activateApelacio(): void
{
    createApelacioTables();
    insertIntoApelacioTables();
}

function deactivateApelacio(): void
{
    dropApelacioTables();
}

function createApelacioTables(): void
{
    //todo транзакция | вытащить запросы от сюда
    $createTypesTableStatement =
        'CREATE TABLE IF NOT EXISTS apelacio_types (
            PRIMARY KEY (id),
            id   TINYINT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL
        );';

    $createProcessingStatusesTableStatement =
        'CREATE TABLE IF NOT EXISTS apelacio_processing_statuses (
            PRIMARY KEY (id),
            id   TINYINT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL
        );';

    $createAppealsTableStatement =
        'CREATE TABLE IF NOT EXISTS apelacio_appeals (
            PRIMARY KEY (id),
            FOREIGN KEY type_id (type_id)
                REFERENCES apelacio_types (id),
            FOREIGN KEY (processing_status_id)
                REFERENCES apelacio_processing_statuses (id),
            id                   INT AUTO_INCREMENT NOT NULL,
            type_id              TINYINT DEFAULT 0 NOT NULL,
            second_name          VARCHAR(255) NOT NULL,
            first_name           VARCHAR(255) NOT NULL,
            patronymic           VARCHAR(255) NOT NULL,
            email                VARCHAR(255) NOT NULL,
            text                 TEXT(65535) NOT NULL,
            processing_status_id TINYINT(1) DEFAULT 1 NOT NULL,
            creation_date        DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            agree_flag           TINYINT DEFAULT 0 NOT NULL,
            delete_flag          TINYINT DEFAULT 0 NOT NULL
        );';

    $createTableConfigList = [
        'types'               => $createTypesTableStatement,
        'processing_statuses' => $createProcessingStatusesTableStatement,
        'appeals'             => $createAppealsTableStatement,
    ];

    foreach ($createTableConfigList as $tableName => $createTableStatement) {
        $tableName .= PREFIX . $tableName;
        createTable($tableName, $createTableStatement);
    }
}

function dropApelacioTables(): void
{
    //todo транзакция | вытащить запросы от сюда
    $dropAppealsTableStatement            = 'DROP TABLE IF EXISTS apelacio_appeals';
    $dropTypesTableStatement              = 'DROP TABLE IF EXISTS apelacio_types';
    $dropProcessingStatusesTableStatement = 'DROP TABLE IF EXISTS apelacio_processing_statuses';

    $dropTableConfigList = [
        'appeals'             => $dropAppealsTableStatement,
        'types'               => $dropTypesTableStatement,
        'processing_statuses' => $dropProcessingStatusesTableStatement,
    ];

    foreach ($dropTableConfigList as $tableName => $dropTableStatement) {
        $tableName .= PREFIX . $tableName;
        dropTable($tableName, $dropTableStatement);
    }
}

function insertIntoApelacioTables(): void
{
    //todo транзакция | вытащить запросы от сюда
    //todo выполнять вставку сразу нескольких записей
    $insertIntoTypesTableStatementList = [
        'INSERT INTO apelacio_types (name) VALUES ("Вопрос");',
        'INSERT INTO apelacio_types (name) VALUES("Жалоба");',
        'INSERT INTO apelacio_types (name) VALUES("Предложение");',
        'INSERT INTO apelacio_types (name) VALUES("Другое");',
    ];

    $insertIntoProcessingStatusesTableStatementList = [
        'INSERT INTO apelacio_processing_statuses (name) VALUES("Обращение принято к рассмотрению");',
        'INSERT INTO apelacio_processing_statuses (name) VALUES("Обращение рассмотрено");',
        'INSERT INTO apelacio_processing_statuses (name) VALUES("Обращение отклонено");',
    ];

    $insertIntoConfigList = [
        'types'               => $insertIntoTypesTableStatementList,
        'processing_statuses' => $insertIntoProcessingStatusesTableStatementList,
    ];

    foreach ($insertIntoConfigList as $tableName => $insertIntoTableStatementList) {
        $tableName = PREFIX . $tableName;
        foreach ($insertIntoTableStatementList as $insertIntoTableStatement) {
            insertIntoTable($tableName, $insertIntoTableStatement);
        }
    }
}

function createTable(string $tableName, string $createTableStatement): void
{
    //todo try-catch | сообщение об ошибка | exception
    global $wpdb;
    if (! $wpdb->query($createTableStatement)) {
        echo 'Не удалось создать таблицу(' . $tableName . ')';// . $mysqli->errno . ') ' . $mysqli->error;
    }
}

function dropTable(string $tableName, string $dropTableStatement): void
{
    //todo try-catch | сообщение об ошибка | exception
    global $wpdb;

    if (! $wpdb->query($dropTableStatement)) {
        echo 'Не удалось удалить таблицу(' . $tableName . ')';// . $mysqli->errno . ') ' . $mysqli->error;
    }
}

function insertIntoTable(string $tableName, string $insertIntoTableStatement): void
{
    //todo try-catch | сообщение об ошибка | exception
    //todo использовать insert
    global $wpdb;

    if (! $wpdb->query($insertIntoTableStatement)) {
        echo 'Не удалось вставить строку в таблицу(' . $tableName . ')' . $wpdb->print_error;// . ') ' . $mysqli->error;
    }
}