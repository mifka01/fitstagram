<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property int|null $active
 * @property int|null $deleted
 * @property int|null $banned
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property User[] $createdBies
 * @property GroupJoinRequest[] $groupJoinRequests
 * @property GroupPost[] $groupPosts
 * @property GroupUsers[] $groupUsers
 * @property User $owner
 * @property Post[] $posts
 * @property User[] $users
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules()
    {
        return [
            [['name', 'owner_id'], 'required'],
            [['owner_id', 'active', 'deleted', 'banned'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
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
            'name' => Yii::t('app', 'Name'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'active' => Yii::t('app', 'Active'),
            'deleted' => Yii::t('app', 'Deleted'),
            'banned' => Yii::t('app', 'Banned'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[CreatedBies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBies()
    {
        return $this->hasMany(User::class, ['id' => 'created_by'])->viaTable('group_join_request', ['group_id' => 'id']);
    }

    /**
     * Gets query for [[GroupJoinRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupJoinRequests()
    {
        return $this->hasMany(GroupJoinRequest::class, ['group_id' => 'id']);
    }

    /**
     * Gets query for [[GroupPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupPosts()
    {
        return $this->hasMany(GroupPost::class, ['group_id' => 'id']);
    }

    /**
     * Gets query for [[GroupUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupUsers()
    {
        return $this->hasMany(GroupUsers::class, ['group_id' => 'id']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['id' => 'post_id'])->viaTable('group_post', ['group_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('group_users', ['group_id' => 'id']);
    }
}
