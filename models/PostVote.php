<?php

namespace app\models;

use app\models\query\PostVoteQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "post_vote".
 *
 * @property int $up
 * @property int $voted_by
 * @property int $post_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Post $post
 * @property User $votedBy
 */
class PostVote extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'post_vote';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['up', 'voted_by', 'post_id'], 'required'],
            [['up', 'voted_by', 'post_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['voted_by', 'post_id'], 'unique', 'targetAttribute' => ['voted_by', 'post_id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
            [['voted_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['voted_by' => 'id']],
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
            'up' => Yii::t('app/model', 'Up'),
            'voted_by' => Yii::t('app/model', 'Voted By'),
            'post_id' => Yii::t('app/model', 'Post ID'),
            'created_at' => Yii::t('app/model', 'Created At'),
            'updated_at' => Yii::t('app/model', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost(): ActiveQuery
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * Gets query for [[VotedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVotedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'voted_by']);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\PostVoteQuery the active query used by this AR class.
     */
    public static function find(): PostVoteQuery
    {
        return new PostVoteQuery(get_called_class());
    }
}
