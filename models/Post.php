<?php

namespace app\models;

use app\models\query\PostQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $created_by
 * @property int $is_group_post
 * @property int $is_private
 * @property int|null $upvote_count
 * @property int|null $downvote_count
 * @property string|null $description
 * @property int|null $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Comment[] $comments
 * @property User[] $commenters
 * @property User $createdBy
 * @property Group[] $groups
 * @property MediaFile[] $mediaFiles
 * @property PostVote[] $votes
 * @property Tag[] $tags
 * @property User[] $voters
 */
class Post extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'post';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['created_by', 'is_private', 'is_group_post'], 'required'],
            [['created_by', 'is_private', 'is_group_post', 'upvote_count', 'downvote_count', 'deleted'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
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
            'id' => 'ID',
            'created_by' => Yii::t('app/model', 'Created By'),
            'is_private' => 'Is Private',
            'is_group_post' => 'Is Group Post',
            'upvote_count' => 'Upvote Count',
            'downvote_count' => 'Downvote Count',
            'description' => 'Description',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommenters(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'created_by'])->viaTable('comment', ['post_id' => 'id']);
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
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups(): ActiveQuery
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])->viaTable('group_post', ['post_id' => 'id']);
    }

    /**
     * Gets query for [[MediaFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMediaFiles(): ActiveQuery
    {
        return $this->hasMany(MediaFile::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[PostVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVotes(): ActiveQuery
    {
        return $this->hasMany(PostVote::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('post_tag', ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoters(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'voted_by'])->viaTable('post_vote', ['post_id' => 'id']);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\PostQuery the active query used by this AR class.
     */
    public static function find(): PostQuery
    {
        return new PostQuery(get_called_class());
    }
}
