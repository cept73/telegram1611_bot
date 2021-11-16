<?php

namespace app\modules\user;

use app\models\User;
use yii\base\BaseObject;

class UserRepository extends BaseObject
{
    public function getByTelegramChat($chat): ?User
    {
        /** @var ?User $user */
        $user = User::find()->where(['id' => $chat->id])->one();
        return $user;
    }
}
