<?php

namespace app\models;

use Yii;

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
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'post_vote';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules()
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
    public function attributeLabels()
    {
        return [
            'up' => Yii::t('app', 'Up'),
            'voted_by' => Yii::t('app', 'Voted By'),
            'post_id' => Yii::t('app', 'Post ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * Gets query for [[VotedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVotedBy()
    {
        return $this->hasOne(User::class, ['id' => 'voted_by']);
    }
}
