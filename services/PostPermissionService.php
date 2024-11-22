<?php

namespace app\services;

use app\models\Post;
use Yii;

class PostPermissionService
{
    public static function checkPostPermission(int $userId, int $postId): bool
    {
        $post = Post::find()->where(['id' => $postId])->deleted(false)->one();

        if (!($post instanceof Post)) {
            return false;
        }

        $createdBy = $post->createdBy;

        if ($createdBy->isBanned() || $createdBy->isDeleted()) {
            return false;
        }

        $group = $post->group;

        if ($group !== null && (!$group->isMember($userId) || $group->isDeleted())) {
            return false;
        }

        if ($userId != Yii::$app->user->id && !$post->isPublic() && !$createdBy->isPermittedUser($userId)) {
            return false;
        }
        return true;
    }
}
