<?php
/**
 * Plugin Name: Simple tickets
 * Plugin URI: https://github.com/sBog787/simple-tickets
 * Description: Plugin provides feedback functionality for visitors of your site.
 * Version: 0.0.1
 * Author: sBog787
 */

/**
 * Class Gateway
 */
class Gateway
{
    /**
     * @var mysqli $dbConnection
     */
    private $dbConnection = null;

    /**
     * Gateway constructor.
     */
    public function __construct()
    {
        $this->dbConnection = new mysqli('127.0.0.1', 'root', 123, 'wp');
        if ($this->dbConnection->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $this->dbConnection->connect_errno . ") " . $this->dbConnection->connect_error;
        }
    }

    /**
     * Method creates a ticket
     *
     * @param int    $typeId
     * @param string $lastName
     * @param string $firstName
     * @param string $patronymic
     * @param string $email
     * @param string $text
     * @param int    $flagAgreed
     *
     * @return void
     */
    public function createTicket(int $typeId, string $lastName, string $firstName, string $patronymic, string $email, string $text, int $flagAgreed): void
    {
        if (! $this->dbConnection->query("INSERT INTO wp_tickets (typeId, lastName, firstName, patronymic, email, text, flagAgreed)
					VALUES ($typeId, '$lastName', '$firstName', '$patronymic', '$email', '$text', $flagAgreed)")) {
            echo 'Can not insert insert new raw: ' . $this->dbConnection->errno . ') ' . $this->dbConnection->error;
        }
    }
}

class TicketService
{
    public function sendAppeal(int $typeId, string $lastName, string $firstName, string $patronymic, string $email, string $text, int $flagAgreed): void
    {
        //TODO использовать try-catch
            $db  = new Gateway();
            $result = $db->createTicket($typeId, $lastName, $firstName, $patronymic, $email, $text, $flagAgreed);
    }
}
