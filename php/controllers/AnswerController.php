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
        (new AppealService())->rejectAppealById($id);
    }

    public function actionSendAnswer(array $request): void
    {
        [
            'id' => $id,
            'answer' => $answer,
        ] = $request;

        if (! isset($id) || ! isset($answer)) {
            return;
        }

        $id = (int)$id;
        (new AppealService())->updateAppealStatusToReviewed($id);
        //mail
    }
}

if (isset($_POST['id'])) {
    if (isset($_POST['answer'])) {
        (new AnswerController())->actionSendAnswer($_POST);
    } else {
        (new AnswerController())->actionRejectAppeal($_POST);
    }
}