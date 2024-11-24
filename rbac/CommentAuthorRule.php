<?php

namespace app\rbac;

use app\models\Comment;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Checks if comment.created_by matches user passed via params
 */
class CommentAuthorRule extends Rule
{
    public $name = 'isCommentAuthor';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array<mixed> $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params): bool
    {
        if (!isset($params['commentId'])) {
            return false;
        }

        return Comment::find()->deleted(false)->banned(false)->andWhere(['id' => $params['commentId']])->andWhere(['created_by' => $user])->exists();
    }
}
