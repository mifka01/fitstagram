<?php

namespace app\models;

use app\models\query\GroupJoinRequestQuery;
use Yii;
use yii\db\ActiveQuery;

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
    public static function tableName(): string
    {
        return 'group_join_request';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
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
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app/group', 'ID'),
            'group_id' => Yii::t('app/group', 'Group ID'),
            'created_by' => Yii::t('app/group', 'Created By'),
            'pending' => Yii::t('app/group', 'Pending'),
            'declined' => Yii::t('app/group', 'Declined'),
            'accepted' => Yii::t('app/group', 'Accepted'),
            'created_at' => Yii::t('app/group', 'Created At'),
            'updated_at' => Yii::t('app/group', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup(): ActiveQuery
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\GroupJoinRequestQuery the active query used by this AR class.
     */
    public static function find(): GroupJoinRequestQuery
    {
        return new GroupJoinRequestQuery(get_called_class());
    }
}
