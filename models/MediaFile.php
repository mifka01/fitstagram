<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media_file".
 *
 * @property int $id
 * @property string|null $media_path
 * @property string $name
 * @property string|null $place
 * @property int $post_id
 *
 * @property Post $post
 */
class MediaFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'media_file';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules()
    {
        return [
            [['name', 'post_id'], 'required'],
            [['post_id'], 'integer'],
            [['media_path', 'name', 'place'], 'string', 'max' => 255],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
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
            'media_path' => Yii::t('app', 'Media Path'),
            'name' => Yii::t('app', 'Name'),
            'place' => Yii::t('app', 'Place'),
            'post_id' => Yii::t('app', 'Post ID'),
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
}
