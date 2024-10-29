<?php

namespace app\models\forms;

use app\models\MediaFile;
use app\models\Post;
use app\models\Tag;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * PostForm is the model behind the post form.
 */
class PostForm extends Model
{
    /**
     * @var \yii\web\UploadedFile[]
     */
    public array $mediaFiles = [];

    public string $group = '';

    public string $description = '';

    public string $tags = '';

    public string $place = '';

    public bool $is_private = false;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {

        return [
            [['mediaFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 5, 'maxSize' => 5 * 1024 * 1024],
            [['place', 'tags'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 512],
            [['is_private'], 'boolean'],
            [['group'], 'integer'],
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
            'place' => Yii::t('app/post', 'Place'),
            'mediaFiles' => Yii::t('app/post', 'Images'),
            'description' => Yii::t('app/post', 'Description'),
            'is_private' => Yii::t('app/post', 'Make the post private'),
            'tags' => Yii::t('app/post', 'Tags'),
            'group' => Yii::t('app/post', 'Group'),
        ];
    }

    /**
     * Save post
     *
     * @return bool
     */
    public function save(): bool
    {
        $user = $this->getCurrentUser();

        $post = new Post();
        $post->description = $this->description;
        $post->is_private = intval($this->is_private);
        $post->group_id = !empty($this->group) ? intval($this->group) : null;
        $post->place = $this->place;
        $post->deleted = 0;
        $post->created_by = $user->id;

        if ($post->save()) {
            return $this->saveMediaFiles($post->id) && $this->saveTags($post);
        }

        return false;
    }

    /**
     * Get the current user.
     *
     * @return User
     * @throws NotFoundHttpException
     */
    private function getCurrentUser(): User
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }

        $user = User::findOne(Yii::$app->user->id);
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }
        return $user;
    }

    private function saveMediaFiles(int $postId): bool
    {
        foreach ($this->mediaFiles as $file) {
            $mediaModel = new MediaFile();
            $filePath = 'images/' . $file->baseName . '.' . $file->extension;
            $mediaModel->media_path = $filePath;
            $mediaModel->name = $file->baseName;
            $mediaModel->post_id = $postId;
            if (!$mediaModel->save()) {
                return false;
            }
        }
        return true;
    }

    private function saveTags(Post $post): bool
    {
        $tagNames = explode(' ', $this->tags);
        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if ($tagName === '') {
                continue;
            }
            $tag = Tag::findOne(['name' => $tagName]);
            if (!$tag) {
                $tag = new Tag();
                $tag->name = $tagName;
                $tag->created_by = $this->getCurrentUser()->id;
                if (!$tag->save()) {
                    return false;
                }
            }
            if (!$post->getTags()->where(['id' => $tag->id])->exists()) {
                $post->link('tags', $tag);
            }
        }
        return true;
    }
}
