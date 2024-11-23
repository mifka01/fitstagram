<?php

namespace app\models;

use app\models\query\CommentQuery;
use app\models\query\GroupJoinRequestQuery;
use app\models\query\GroupQuery;
use app\models\query\PostQuery;
use app\models\query\UserQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $owner_id
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
            [['owner_id', 'deleted'], 'integer'],
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
            'id' => Yii::t('app/group', 'ID'),
            'name' => Yii::t('app/group', 'Name'),
            'description' => Yii::t('app/group', 'Description'),
            'owner_id' => Yii::t('app/group', 'Owner ID'),
            'deleted' => Yii::t('app/group', 'Deleted'),
            'banned' => Yii::t('app/group', 'Banned'),
            'created_at' => Yii::t('app/group', 'Created At'),
            'updated_at' => Yii::t('app/group', 'Updated At'),
        ];
    }

    /**
     * Checks if the group is deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted == 1;
    }

    /**
     * Checks if the group is deleted.
     *
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned == 1;
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
     * @return GroupJoinRequestQuery
     */
    public function getGroupJoinRequests(): ActiveQuery
    {
        /** @var GroupJoinRequestQuery $query */
        $query = $this->hasMany(GroupJoinRequest::class, ['group_id' => 'id'])
            ->joinWith('createdBy')
            ->andWhere([
                'user.banned' => false,
                'user.deleted' => false,
                'user.active' => true,
            ]);
        return $query;
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
     * Checks if the user is the owner of the group.
     *
     * @param int|null|string $userId
     * @return bool
     */
    public function isOwner($userId): bool
    {
        return $this->owner_id === $userId;
    }

    /**
     * Gets query for [[Users]].
     *
     * @return UserQuery
     */
    public function getMembers(): ActiveQuery
    {
        /** @var UserQuery $query */
        $query = $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('group_member', ['group_id' => 'id']);
        return $query;
    }

    /**
     * Checks if the user is a member of the group.
     *
     * @param int $userId
     * @return bool
     */
    public function isMember(int $userId): bool
    {
        return $this->getMembers()->andWhere(['id' => $userId])->exists();
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return PostQuery
     */
    public function getPosts(): ActiveQuery
    {
        /** @var PostQuery $query */
        $query = $this->hasMany(Post::class, ['group_id' => 'id']);
        return $query;
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return CommentQuery
     */
    public function getComments(): ActiveQuery
    {
        /** @var CommentQuery $query */
        $query = $this->hasMany(Comment::class, ['post_id' => 'id'])
            ->viaTable('post', ['group_id' => 'id']);
        return $query;
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
