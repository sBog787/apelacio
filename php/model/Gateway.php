<?php

//TODO ПОЧИТАТЬ ДОКУМЕНТАЦИЮ О MYSQLI
//todo сделать фасад для доступа к шлюзу

/**
 * Шлюз таблицы данных.
 * TODO не отображается кириллица в бд
 */
class Gateway
{
    /**
     * Соединение с бд.
     * TODO мб статик
     *
     * @var mysqli|null
     */
    private $dbConnection = null;

    /**
     *  Строка запроса для вставки новой записи об тикете в бд.
     *
     * @var string
     */
    private static $insertTicketStatement =
        "INSERT INTO wp_tickets (typeId, secondName, firstName, patronymic, email, text, flagAgreed)
		 VALUES (%TYPEID%, '%LASTNAME%', '%FIRSTNAME%', '%PATRONYMIC%', '%EMAIL%', '%TEXT%', %FLAGAGREED%)";

    /**
     * Строка запроса для выборки записей о тикетах из бд.
     *
     * @var string
     */
    private static $findTicketsStatement =
        "SELECT id, typeId, secondName, firstName, patronymic, email, text
         FROM wp_tickets 
         WHERE statusId = 0"; //надо ли asc | подумать над запросом | получать не все сразу, а только необходимые атрибуты

    /**
     * Конструктор устанавливает соединение с бд.
     * TODO обработка ошибок | exception
     */
    public function __construct()
    {
        $this->dbConnection = new mysqli('127.0.0.1', 'root', 123, 'wp');
        if ($this->dbConnection->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $this->dbConnection->connect_errno . ") " . $this->dbConnection->connect_error;
        }
    }

    /**
     * Метод выполняет вставку новой записи для тикета в бд.
     *
     * @param int    $typeId     Тип обращения.
     * @param string $lastName   Фамилия гражданина.
     * @param string $firstName  Имя гражданина.
     * @param string $patronymic Отчество гражданина.
     * @param string $email      Адрес электронной почты гражданина.
     * @param string $text       Содержимое тикета.
     * @param int    $flagAgreed Флаг согласия с политикой конфиденциальности персональных данных образовательной организации.
     *
     * todo exception
     * @return void
     */
    public function insertTicket(int $typeId, string $lastName, string $firstName, string $patronymic, string $email, string $text, int $flagAgreed): void
    {
        $query = str_replace([
            '%TYPEID%',
            '%LASTNAME%',
            '%FIRSTNAME%',
            '%PATRONYMIC%',
            '%EMAIL%',
            '%TEXT%',
            '%FLAGAGREED%',
        ], [
            $typeId,
            $lastName,
            $firstName,
            $patronymic,
            $email,
            $text,
            $flagAgreed,
        ], self::$insertTicketStatement);

        $this->dbConnection->query($query); //throw exception
    }

    /**
     * Метод выполняет выборку записей о необработанных тикетах из бд.
     * todo сделать универсальнее
     * todo exceptions
     * todo типизация
     */
    public function selectUncheckedTickets()
    {
        $result = $this->dbConnection->query(self::$findTicketsStatement); //throw exception
        return $result;
    }
}
