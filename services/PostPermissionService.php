<?php

namespace app\services;

use app\models\Post;

class PostPermissionService
{
    /**
     * Check post permission
     *
     * @param int|null|string $userId
     * @param int $postId
     * @return bool
     */
    public static function checkPostPermission($userId, int $postId): bool
    {
        $post = Post::find()->where(['id' => $postId])->deleted(false)->banned(false)->one();

        if (!($post instanceof Post)) {
            return false;
        }

        $createdBy = $post->createdBy;

        if ($createdBy->isBanned() || $createdBy->isDeleted()) {
            return false;
        }

        $group = $post->group;


        // Guest
        if ($userId === null) {
            return $post->isPublic() && $group === null;
        }

        // Invalid user id (string)
        if (!is_int($userId)) {
            return false;
        }


        // Owner
        if ($userId === $createdBy->id) {
            return true;
        }

        // User
        if ($group !== null && (!$group->isMember($userId) || $group->isDeleted())) {
            return false;
        }

        if ($post->isPrivate() && !$createdBy->isPermittedUser($userId)) {
            return false;
        }

        return true;
    }
}
