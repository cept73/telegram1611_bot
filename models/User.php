<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class User
 *
 * @package app\models
 * @property $id
 * @property $first_name
 * @property $last_name
 * @property $username
 * @property $current_doc
 * @property $created_at
 * @property null|string $currentDocumentId
 * @property-write mixed $userInfo
 * @property $updated_at
 */
class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'user';
    }

    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function setId($id): void
    {
        $this->setAttribute('id', $id);
    }

    public function setUserInfo($chat): void
    {
        $this->setAttribute('username', $chat->username);
        $this->setAttribute('first_name', $chat->first_name);
        $this->setAttribute('last_name', $chat->last_name);
    }

    public function setUpdatedAt(): void
    {
        $this->setAttribute('updated_at', new Expression('now()'));
    }

    public function setCreatedAt(): void
    {
	    $this->setAttribute('created_at', new Expression('now()'));
    }
}
