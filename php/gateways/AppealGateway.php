<?php

require_once __DIR__ . "/../../../../../wp-load.php";

//TODO ПОЧИТАТЬ ДОКУМЕНТАЦИЮ О MYSQLI
//todo сделать фасад для доступа к шлюзу

class AppealGateway
{
    private $dbConnection = null;

    private static $insertNewAppealStatement =
        'INSERT INTO apelacio_appeals (type_id, second_name, first_name, patronymic, email, text, agree_flag)
		 VALUES (%TYPEID%, "%SECONDNAME%", "%FIRSTNAME%", "%PATRONYMIC%", "%EMAIL%", "%TEXT%", %AGREEFLAG%)';

    private static $findIncomingAppealListStatement =
        'SELECT *
         FROM apelacio_appeals
         WHERE processing_status_id = 1 AND delete_flag = 0
         ORDER BY id DESC';

    private static $updateAppealtStatusToRejectStatement =
        'UPDATE apelacio_appeals
            SET processing_status_id = 3
            WHERE id = %d';

    private static $updateAppealStatusToReviewedStatement =
        'UPDATE apelacio_appeals
            SET processing_status_id = 2
            WHERE id = %d';

    private static $findAppealByIdStatement =
        'SELECT a.id, a.type_id, a.second_name, a.first_name, a.patronymic, a.email, a.text, a.processing_status_id, a.creation_date, a.agree_flag, a.delete_flag, t.name
        FROM apelacio_appeals AS a
        INNER JOIN apelacio_types AS t
        ON a.type_id = t.id
        WHERE a.id = %d AND a.delete_flag <> 1';

    //todo добавить запрос для вставки новой записи в таблицу ответов

    //TODO обработка ошибок | exception
    public function __construct()
    {
        global $wpdb;
        $this->dbConnection = $wpdb;
    }

    public function insertNewAppea(int $typeId, string $lastName, string $firstName, string $patronymic, string $email, string $text, int $flagAgreed): void
    {
        $query = str_replace([
            '%TYPEID%',
            '%SECONDNAME%',
            '%FIRSTNAME%',
            '%PATRONYMIC%',
            '%EMAIL%',
            '%TEXT%',
            '%AGREEFLAG%',
        ], [
            $typeId,
            $lastName,
            $firstName,
            $patronymic,
            $email,
            $text,
            $flagAgreed,
        ], self::$insertNewAppealStatement);

        $this->dbConnection->query($query);
    }

    // todo сделать универсальнее
    // todo exceptions
    //todo типизация
    public function findUncheckedAppeals()
    {
        $result = $this->dbConnection->get_results(self::$findIncomingAppealListStatement, 'ARRAY_A'); //throw exception
        return $result;
    }

    public function findAppealById(int $id)
    {
        $query = self::$findAppealByIdStatement;
        $query = $this->dbConnection->prepare($query, $id);
        $result = $this->dbConnection->get_results($query, 'ARRAY_A');
        return $result;
    }

    public function rejectAppealById(int $id): void
    {
        $query = self::$updateAppealtStatusToRejectStatement;
        $query = $this->dbConnection->prepare($query, $id);
        $this->dbConnection->query($query);
    }

    public function updateAppealStatusToReviewed(int $id): void
    {
        $query = self::$updateAppealStatusToReviewedStatement;
        $query = $this->dbConnection->prepare($query, $id);
        $this->dbConnection->query($query);
    }
}
