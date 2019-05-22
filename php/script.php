<?php

//подумать, как это обрабатывать
include 'TicketService.php';

[
    'appealTypeId' => $typeId,
    'lastName'     => $secondName,
    'firstName'    => $firstName,
    'patronymic'   => $patronymic,
    'email'        => $email,
    'text'         => $text,
    'terms'        => $flagAgreed,
] = $_POST;

if (isset($flagAgreed) && $flagAgreed === 'on') {
    $flagAgreed = (int)$flagAgreed;
    ( new TicketService() )->createNewTicket((int)$typeId, $secondName, $firstName, $patronymic, $email, $text, $flagAgreed);
}
