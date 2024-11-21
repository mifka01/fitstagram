<?php

namespace app\services;

use app\models\Post;
use Yii;

class PostPermissionService
{
    public static function checkPostPermission(int $userId, int $postId): bool
    {
        $post = Post::find()->where(['id' => $postId])->deleted(false)->one();

        if ($post instanceof Post && $post->group !== null && !$post->group->isMember($userId)) {
            return false;
        }

        if ($userId != Yii::$app->user->id && $post instanceof Post && !$post->isPublic() && ($createdBy = $post->createdBy) !== null && !$createdBy->isPermittedUser($userId)) {
            return false;
        }
        return true;
    }
}
