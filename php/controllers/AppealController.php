<?php

require_once __DIR__ . '/../models/AppealService.php';

class AppealController
{
    public function actionCreateAppeal(array $request): void
    {
        [
            'typeId'     => $typeId,
            'secondName' => $secondName,
            'firstName'  => $firstName,
            'patronymic' => $patronymic,
            'email'      => $email,
            'text'       => $text,
            'flagAgreed' => $flagAgreed,
        ] = $request;

        if (isset($flagAgreed) && $flagAgreed === 'on') {
            $flagAgreed = 1;
            $typeId     = (int)$typeId;
            ( new AppealService() )->createNewAppeal($typeId, $secondName, $firstName, $patronymic, $email, $text, $flagAgreed);
        }
    }

    public function actionFindAppeal(array $request): array
    {
        [
            'id' => $id,
        ] = $request;

        if (! isset($id)) {
            return [];
        }

        $id     = (int)$id;
        $result = ( new AppealService() )->findAppealById($id);

        return $result;
    }
}

if (isset($_POST['id'])) {
    $result = ( new AppealController() )->actionFindAppeal($_POST);

    echo json_encode($result);
} else {
    ( new AppealController )->actionCreateAppeal($_POST);
}
