<?php

namespace app\rbac;

use app\models\Post;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Checks if post.created_by matches user passed via params
 */
class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array<mixed> $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params): bool
    {
        if (!isset($params['postId'])) {
            return false;
        }

        return Post::find()->deleted(false)->banned(false)->andWhere(['id' => $params['postId']])->andWhere(['created_by' => $user])->exists();
    }
}
