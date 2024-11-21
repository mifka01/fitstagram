<?php

namespace app\models\forms;

use app\models\Comment;
use app\models\User;
use app\services\PostPermissionService;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * CommentForm is the model behind the comment form.
 */
class CommentForm extends Model
{
    public string $content = '';

    public int $postId = -1;

    private ?Comment $comment = null;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {

        return [
            [['content'], 'string', 'max' => 512],
            [['content', 'postId'], 'required'],
            [['postId'], 'integer'],
        ];
    }

    /**
     * Save post
     *
     * @return bool
     */
    public function save(): bool
    {
        $userId = $this->getCurrentUser()->id;

        if (!PostPermissionService::checkPostPermission($userId, $this->postId)) {
            throw new NotFoundHttpException(Yii::t('app/error', 'Post not found.'));
        }

        $comment = new Comment();
        $comment->content = $this->content;
        $comment->post_id = $this->postId;
        $comment->created_by = $userId;

        if ($comment->save()) {
            $this->comment = $comment;
            return true;
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

    public function getComment(): ?Comment
    {
        return $this->comment;
    }
}
