<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as BaseActiveRecord;

class TimestampRecord extends BaseActiveRecord
{
    /**
     * {@inheritDoc}
     *
     * @return array<mixed>
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }
}
