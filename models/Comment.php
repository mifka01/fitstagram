<?php

namespace app\models;

use app\models\query\CommentQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string $content
 * @property int $created_by
 * @property int $post_id
 * @property int|null $deleted
 * @property int|null $banned
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property Post $post
 */
class Comment extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'comment';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['content', 'created_by', 'post_id'], 'required'],
            [['created_by', 'post_id', 'deleted', 'banned'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['content'], 'string', 'max' => 512],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
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
            'content' => Yii::t('app/model', 'Content'),
            'created_by' => Yii::t('app/model', 'Created By'),
            'post_id' => Yii::t('app/model', 'Post ID'),
            'deleted' => Yii::t('app/model', 'Deleted'),
            'banned' => Yii::t('app/model', 'Banned'),
            'created_at' => Yii::t('app/model', 'Created At'),
            'updated_at' => Yii::t('app/model', 'Updated At'),
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
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost(): ActiveQuery
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\CommentQuery the active query used by this AR class.
     */
    public static function find(): CommentQuery
    {
        return new CommentQuery(get_called_class());
    }
}
