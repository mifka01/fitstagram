<?php

namespace app\models\forms;

use app\models\forms\MediaFileForm;
use app\models\Group;
use app\models\Post;
use app\models\Tag;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
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

    /**
     * @var array<string> $tags
     */
    private array $tags = [];

    public string $place = '';

    public bool $is_private = false;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {

        return [
            [['mediaFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, webp, gif', 'maxFiles' => 5, 'maxSize' => 5 * 1024 * 1024],
            [['mediaFiles'], 'required'],
            [['place'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 512],
            [['is_private'], 'boolean'],
            [['is_private'], 'required', 'requiredValue' => 0, 'when' => function ($model) {
                return !empty($model->group);
            }, 'whenClient' => 'function (attribute, value) { return $("#postform-group").val() !== ""; }',
                'message' => Yii::t('app/post', 'You cannot make a private post in a group.')],
            ['tags', 'each', 'rule' => ['match', 'pattern' => '/^#?[a-zA-Z0-9_]+$/']],
            [['group'], 'integer']
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
            'is_private' => Yii::t('app/post', 'Private'),
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
        if (!empty($this->group)) {
            $group = Group::find()->deleted(false)->active(true)->andWhere(['id' => $this->group])->one();
            if (!($group instanceof Group)) {
                throw new NotFoundHttpException(Yii::t('app/error', 'Group not found.'));
            }
            if (!$group->isMember($user->id)) {
                throw new ForbiddenHttpException(Yii::t('app/error', 'You do not have rights to post to this group.'));
            }
            $post->group_id = intval($this->group);
        } else {
            $post->group_id = null;
        }
        $post->place = $this->place;
        $post->deleted = 0;
        $post->banned = 0;
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
            $mediaModel = new MediaFileForm(MediaFileForm::DIR_PATH_POST);
            $mediaModel->file = $file;
            $mediaModel->postId= $postId;

            if (!$mediaModel->upload()) {
                return false;
            }
        }
        return true;
    }

    private function saveTags(Post $post): bool
    {
        foreach ($this->tags as $tagName) {
            $tagName = trim($tagName, " \n\r\t\v\x00#");
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

    /**
     * @param array<string>|string $tags
     */
    public function setTags($tags): void
    {
        $this->tags = is_array($tags) ? $tags : [];
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
