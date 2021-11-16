<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Listing
 *
 * @package app\models
 * @property $id
 * @property $ticker
 * @property $assets
 * @property $loans
 * @property $cap
 * @property $change_num
 * @property $changed_at
 */
class Listing extends ActiveRecord
{
    public const RATIO1_HALAL_PERCENT = 33;
    public const RATIO2_HALAL_PERCENT = 33;
    public const TEXT_HALAL = 'Халяль';
    public const TEXT_HARAM = 'Харам';

    public static function tableName(): string
    {
        return 'listing';
    }
}
