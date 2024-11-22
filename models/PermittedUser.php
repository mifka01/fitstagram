<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permitted_user".
 *
 * @property int $user_id
 * @property int $permitted_user_id
 *
 * @property User $permittedUser
 * @property User $user
 */
class PermittedUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'permitted_user';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['user_id', 'permitted_user_id'], 'required'],
            [['user_id', 'permitted_user_id'], 'integer'],
            [['user_id', 'permitted_user_id'], 'unique', 'targetAttribute' => ['user_id', 'permitted_user_id']],
            [['permitted_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['permitted_user_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function attributeLabels(): array
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'permitted_user_id' => Yii::t('app', 'Permitted User ID'),
        ];
    }

    /**
     * Gets query for [[PermittedUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermittedUser()
    {
        return $this->hasOne(User::class, ['id' => 'permitted_user_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
