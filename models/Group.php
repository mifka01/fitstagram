<?php

namespace app\models;

use app\models\query\GroupQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $owner_id
 * @property int|null $active
 * @property int|null $deleted
 * @property int|null $banned
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property User[] $joinRequestUsers
 * @property GroupJoinRequest[] $groupJoinRequests
 * @property User $owner
 * @property Post[] $posts
 * @property User[] $members
 */
class Group extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'group';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['name', 'owner_id'], 'required'],
            [['owner_id', 'active', 'deleted', 'banned'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 512],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
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
            'id' => Yii::t('app/model', 'ID'),
            'name' => Yii::t('app/model', 'Name'),
            'description' => Yii::t('app/model', 'Description'),
            'owner_id' => Yii::t('app/model', 'Owner ID'),
            'active' => Yii::t('app/model', 'Active'),
            'deleted' => Yii::t('app/model', 'Deleted'),
            'banned' => Yii::t('app/model', 'Banned'),
            'created_at' => Yii::t('app/model', 'Created At'),
            'updated_at' => Yii::t('app/model', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[JoinRequestUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJoinRequestUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'created_by'])->viaTable('group_join_request', ['group_id' => 'id']);
    }

    /**
     * Gets query for [[GroupJoinRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupJoinRequests(): ActiveQuery
    {
        return $this->hasMany(GroupJoinRequest::class, ['group_id' => 'id']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts(): ActiveQuery
    {
        return $this->hasMany(Post::class, ['id' => 'post_id'])->viaTable('group_post', ['group_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMembers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('group_member', ['group_id' => 'id']);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\GroupQuery the active query used by this AR class.
     */
    public static function find(): GroupQuery
    {
        return new GroupQuery(get_called_class());
    }
}
