<?php

namespace app\models;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $created_by
 * @property int $visibility
 * @property int|null $upvote_count
 * @property int|null $downvote_count
 * @property string|null $description
 * @property int|null $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Comment[] $comments
 * @property User[] $createdBies
 * @property User $createdBy
 * @property GroupPost[] $groupPosts
 * @property Group[] $groups
 * @property MediaFile[] $mediaFiles
 * @property PostTag[] $postTags
 * @property PostVote[] $postVotes
 * @property Tag[] $tags
 * @property User[] $votedBies
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules()
    {
        return [
            [['created_by', 'visibility'], 'required'],
            [['created_by', 'visibility', 'upvote_count', 'downvote_count', 'deleted'], 'integer'],
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
            'created_by' => 'Created By',
            'visibility' => 'Visibility',
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
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBies()
    {
        return $this->hasMany(User::class, ['id' => 'created_by'])->viaTable('comment', ['post_id' => 'id']);
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
     * Gets query for [[GroupPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupPosts()
    {
        return $this->hasMany(GroupPost::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])->viaTable('group_post', ['post_id' => 'id']);
    }

    /**
     * Gets query for [[MediaFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMediaFiles()
    {
        return $this->hasMany(MediaFile::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[PostTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[PostVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostVotes()
    {
        return $this->hasMany(PostVote::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('post_tag', ['post_id' => 'id']);
    }

    /**
     * Gets query for [[VotedBies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVotedBies()
    {
        return $this->hasMany(User::class, ['id' => 'voted_by'])->viaTable('post_vote', ['post_id' => 'id']);
    }
}
