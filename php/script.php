<?php

//подумать, как это обрабатывать
include 'model/TicketService.php';

[
    'appealTypeId' => $typeId,
    'secondName'     => $secondName,
    'firstName'    => $firstName,
    'patronymic'   => $patronymic,
    'email'        => $email,
    'text'         => $text,
    'flagAgreed'   => $flagAgreed,
] = $_POST;

if (isset($flagAgreed) && $flagAgreed === 'on') {
    $flagAgreed = 1;
    $typeId     = (int)$typeId;
    ( new TicketService() )->createNewTicket($typeId, $secondName, $firstName, $patronymic, $email, $text, $flagAgreed);
}
