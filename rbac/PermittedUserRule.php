<?php

namespace app\rbac;

use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Checks if user passed via params is permitted to access another users post
 */
class PermittedUserRule extends Rule
{
    public $name = 'isPermittedUser';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array<mixed> $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params): bool
    {
        if (!isset($params['userId'])) {
            return false;
        }

        if ($user == $params['userId']) {
            return true;
        }

        return Yii::$app->db->createCommand(
            'SELECT COUNT(*)
            FROM {{%permitted_user}}
            WHERE user_id = :userId AND permitted_user_id = :permittedUserId',
            [':userId' => $params['userId'], ':permittedUserId' => $user]
        )->queryScalar() > 0;
    }
}
