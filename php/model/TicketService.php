<?php

include 'Gateway.php'; //todo что-то с этим сделать

/**
 * Класс сценариев транзакций.
 */
class TicketService
{
    /**
     * Метод выполняет создание нового тикета.
     * TODO ВАЛИДАЦИЯ
     * TODO try catch & exceptions
     *
     * @param int    $typeId     Тип обращения
     * @param string $lastName   Фамилия гражданина.
     * @param string $firstName  Имя гражданина.
     * @param string $patronymic Отчество гражданина.
     * @param string $email      Адрес электронной почты гражданина.
     * @param string $text       Содержимое тикета.
     * @param int    $flagAgreed Флаг согласия с политикой конфиденциальности персональных данных образовательной организации.
     *
     * @return void
     */
    public function createNewTicket(int $typeId, string $lastName, string $firstName, string $patronymic, string $email, string $text, int $flagAgreed): void
    {
        //validation
        ( new Gateway() )->insertTicket($typeId, $lastName, $firstName, $patronymic, $email, $text, $flagAgreed);
    }

    /**
     * Метод выполняет поиск необработанных тикетов.
     * TODO TRY|CATCH
     *
     * @return array
     */
    public function findUncheckedTickets(): array
    {
        $raws = ( new Gateway() )->selectUncheckedTickets();
        if ($raws === false) {
            return [];
        }
        $result = [];
        for ($rawNumber = $raws->num_rows - 1; $rawNumber >= 0; $rawNumber --) {
            $raws->data_seek($rawNumber); //разобраться в алгоритме
            $result[] = $raws->fetch_assoc();
        }
        return $result;
    }

    //close

    //answer
}
