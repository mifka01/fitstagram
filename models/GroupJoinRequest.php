<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group_join_request".
 *
 * @property int $id
 * @property int $group_id
 * @property int $created_by
 * @property int|null $pending
 * @property int|null $declined
 * @property int|null $accepted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property Group $group
 */
class GroupJoinRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'group_join_request';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules()
    {
        return [
            [['group_id', 'created_by'], 'required'],
            [['group_id', 'created_by', 'pending', 'declined', 'accepted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'group_id'], 'unique', 'targetAttribute' => ['created_by', 'group_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'group_id' => Yii::t('app', 'Group ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'pending' => Yii::t('app', 'Pending'),
            'declined' => Yii::t('app', 'Declined'),
            'accepted' => Yii::t('app', 'Accepted'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
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
}
