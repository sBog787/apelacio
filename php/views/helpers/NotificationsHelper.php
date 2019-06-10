<?php

class NotificationsHelper
{
    /**
     * @var array Данные тикетов.
     */
    private $appealDataList = [];

    /**
     * Конструктор добавляет полное имя гражданина для каждых данных тикетов.
     *
     * @param array $appealDataList
     */
    public function __construct(array $appealDataList)
    {
        $appealDataWithFullNameList = [];
        foreach ($appealDataList as &$appealData) {
            $fullName                     = $this->createFullName($appealData);
            $appealData['fullName']       = $fullName;
            $appealDataWithFullNameList[] = $appealData;
        }
        $this->appealDataList = $appealDataWithFullNameList;
    }

    /**
     * Метод возвращает хтмл преставляющий уведомления о новых тикетах.
     *
     * @return void
     */
    public function viewNotificationList(): void
    {
        $appealDataList = $this->getAppealDataList();
        $result         = '';
        foreach ($appealDataList as $appealData) {
            //todo что-то с ид сделать
            $appealId           = $appealData['id'];
            $notificationLinkId = 'notificationRef_' . $appealId;
            $result             .=
                '<div id="message" class="notice notice-success is-dismissible" data-id="' . $appealId . '">
                    <p>
                        Новое обращение от ' . $appealData['fullName'] . '
                        <a id="' . $notificationLinkId . '" href="#" class="notificationRef">Просмотреть</a>
                    </p>
                </div>';
        }
        echo $result;
    }

    /**
     * Геттер получения списка данных тикетов.
     *
     * @return array
     */
    public function getAppealDataList(): array
    {
        return $this->appealDataList;
    }

    /**
     * Метод создает полное имя.
     *
     * @param array $appealData
     *
     * @return string
     */
    function createFullName(array $appealData): string
    {
        $secondName = $appealData['second_name'];
        $firstName  = $appealData['first_name'];
        $patronymic = $appealData['patronymic'];
        $fullName   = $secondName . ' ' . mb_substr($firstName, 0, 1) . '.' . mb_substr($patronymic, 0, 1) . '.';
        return $fullName;
    }
}