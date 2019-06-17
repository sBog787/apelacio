<?php

require_once __DIR__ . '/../models/AppealService.php';

class AnswerController
{
    public function actionRejectAppeal(array $request): void
    {
        [
            'id' => $id,
        ] = $request;

        if (! isset($id)) {
            return;
        }

        $id = (int)$id;
        ( new AppealService() )->rejectAppealById($id);
    }

    public function actionSendAnswer(array $request): void
    {

        [
            'id'     => $id,
            'answer' => $answer,
            'appeal' => $appeal,
        ] = $request;

        if (! isset($id) || ! isset($answer)) {
            return;
        }

        $id     = (int)$id;
        $result = ( new AppealService() )->findAppealById($id);
        ( new AppealService() )->updateAppealStatusToReviewed($id);
        $email   = $result[0]['email'];
        $message = 'Ваше обращение: ' . $appeal . "\n\n";
        $message .= 'Ответ на обращение: ' . $answer;
        $subject = '=?utf-8?b?' . base64_encode('ТОИПКРО: ваша завка рассмотрена') . '?=';
        mail($email, $subject, $message);
    }
}

if (isset($_POST['id'])) {
    if (isset($_POST['answer'])) {
        ( new AnswerController() )->actionSendAnswer($_POST);
    } else {
        ( new AnswerController() )->actionRejectAppeal($_POST);
    }
}
