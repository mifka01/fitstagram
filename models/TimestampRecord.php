<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as BaseActiveRecord;
use yii\db\Expression;

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
            [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value' => new Expression('NOW()')
            ]
        ];
    }
}
