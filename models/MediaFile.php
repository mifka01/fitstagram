<?php

namespace app\models;

use app\models\query\MediaFileQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "media_file".
 *
 * @property int $id
 * @property string|null $path
 * @property string $name
 * @property int $post_id
 *
 * @property Post $post
 */
class MediaFile extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'media_file';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['name', 'post_id'], 'required'],
            [['post_id'], 'integer'],
            [['path', 'name'], 'string', 'max' => 255],
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
            'path' => Yii::t('app/model', 'Media Path'),
            'name' => Yii::t('app/model', 'Name'),
            'post_id' => Yii::t('app/model', 'Post ID'),
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
     * {@inheritDoc}
     *
     * @return \app\models\query\MediaFileQuery the active query used by this AR class.
     */
    public static function find(): MediaFileQuery
    {
        return new MediaFileQuery(get_called_class());
    }
}
