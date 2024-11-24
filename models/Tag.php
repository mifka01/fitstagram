<?php

namespace app\models;

use app\models\Post;
use app\models\query\PostQuery;
use app\models\query\TagQuery;
use app\models\query\UserQuery;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property int $created_by
 * @property string $name
 * @property string $created_at
 *
 * @property User $createdBy
 * @property Post[] $posts
 */
class Tag extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'tag';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['created_by', 'name'], 'required'],
            [['created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
            'id' => Yii::t('app/model', 'ID'),
            'created_by' => Yii::t('app/model', 'Created By'),
            'name' => Yii::t('app/model', 'Name'),
            'created_at' => Yii::t('app/model', 'Created At'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return UserQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        /** @var UserQuery $query */
        $query = $this->hasOne(User::class, ['id' => 'created_by']);
        return $query;
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return PostQuery
     */
    public function getPosts(): ActiveQuery
    {
        /** @var PostQuery $query */
        $query = $this->hasMany(Post::class, ['id' => 'post_id'])->viaTable('post_tag', ['tag_id' => 'id']);
        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\TagQuery the active query used by this AR class.
     */
    public static function find(): TagQuery
    {
        return new TagQuery(get_called_class());
    }
}
