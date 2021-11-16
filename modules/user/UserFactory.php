<?php

namespace app\modules\user;

use app\models\User;
use yii\base\BaseObject;

class UserFactory extends BaseObject
{
    public function createByTelegramChat($chat): ?User
    {
        $user = new User();
        $user->setId($chat->id);
        $user->setUserInfo($chat);
        $user->setCreatedAt();
        $user->setUpdatedAt();

        $user->save();

        return $user;
    }
}
