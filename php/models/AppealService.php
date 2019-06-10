<?php

require_once __DIR__ . '/../gateways/AppealGateway.php'; //todo что-то с этим сделать

/**
 * Класс сценариев транзакций.
 */
class AppealService
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
    public function createNewAppeal(int $typeId, string $lastName, string $firstName, string $patronymic, string $email, string $text, int $flagAgreed): void
    {
        //validation
        ( new AppealGateway() )->insertNewAppea($typeId, $lastName, $firstName, $patronymic, $email, $text, $flagAgreed);
    }

    public function findUncheckedAppeals(): array
    {
        $raws = ( new AppealGateway() )->findUncheckedAppeals();
        if ($raws === false) {
            return [];
        }
//        $result = [];
//        for ($rawNumber = $raws->num_rows - 1; $rawNumber >= 0; $rawNumber --) {
//            $raws->data_seek($rawNumber); //разобраться в алгоритме
//            $result[] = $raws->fetch_assoc();
//        }
        return $raws;
    }

    public function findAppealById(int $id): array
    {
        $raw = ( new AppealGateway() )->findAppealById($id);

        if ($raw === false) {
            return [];
        }

        return $raw;
    }

    public function rejectAppealById(int $id): void
    {
        (new AppealGateway())->rejectAppealById($id);
    }

    public function updateAppealStatusToReviewed(int $id): void
    {
        (new AppealGateway())->updateAppealStatusToReviewed($id);
    }
}
