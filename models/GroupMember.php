<?php

namespace app\models;

use app\models\query\GroupMemberQuery;
use Yii;

/**
 * This is the model class for table "group_member".
 *
 * @property int $group_id
 * @property int $user_id
 * @property string $created_at
 *
 * @property Group $group
 * @property User $user
 */
class GroupMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'group_member';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['group_id', 'user_id'], 'required'],
            [['group_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['group_id', 'user_id'], 'unique', 'targetAttribute' => ['group_id', 'user_id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
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
            'group_id' => Yii::t('app/group', 'Group ID'),
            'user_id' => Yii::t('app/group', 'User ID'),
            'created_at' => Yii::t('app/group', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
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

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\GroupMemberQuery the active query used by this AR class.
     */
    public static function find(): GroupMemberQuery
    {
        return new GroupMemberQuery(get_called_class());
    }
}
