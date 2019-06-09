<?php
/**
 * Plugin Name: Apelacio
 * Plugin URI: https://github.com/sBog787/apelacio
 * Description: Plugin provides feedback functionality for visitors of your site. todo переписать
 * Version: 0.0.1
 * Author: sBog787
 */

register_activation_hook(__FILE__, 'activateApelacio');

register_deactivation_hook(__FILE__, 'deactivateApelacio');

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
            id                   INT NOT NULL,
            type_id              TINYINT DEFAULT 0 NOT NULL,
            second_name          VARCHAR(255) NOT NULL,
            first_name           VARCHAR(255) NOT NULL,
            patronymic           VARCHAR(255) NOT NULL,
            email                VARCHAR(255) NOT NULL,
            text                 TEXT(65535) NOT NULL,
            processing_status_id TINYINT(1) DEFAULT 0 NOT NULL,
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
