<?php

namespace app\modules\user;

use app\models\User;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class UserService extends BaseObject
{
    /**
     * @param $chat
     * @return User|null
     * @throws InvalidConfigException
     */
    public static function getOrCreateUserByChat($chat): ?User
    {
        if (!$chat) {
            return null;
        }

        $userRepository = Yii::createObject(UserRepository::class);
        $currentUser = $userRepository->getByTelegramChat($chat);
        if (!$currentUser) {
            $userFactory = Yii::createObject(UserFactory::class);
            $currentUser = $userFactory->createByTelegramChat($chat);
        } elseif (self::isUserChangedInChat($currentUser, $chat)) {
            $currentUser->setUserInfoFromChat($chat);
	        $currentUser->setUpdatedAt();
            $currentUser->save();
        }

        return $currentUser;
    }

    public static function isUserChangedInChat($user, $chat): bool
    {
        $attributes = ['username', 'first_name', 'last_name'];
        foreach ($attributes as $attr) {
            if ($chat->$attr !== $user[$attr]) {
                return true;
            }
        }

        return false;
    }
}
