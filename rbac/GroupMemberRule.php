<?php

namespace app\rbac;

use app\models\GroupMember;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Checks if user passed via params is a member of the group
 */
class GroupMemberRule extends Rule
{
    public $name = 'isGroupMember';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array<mixed> $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params): bool
    {
        if (!isset($params['groupId'])) {
            return false;
        }

        return GroupMember::find()->byUser((int)$user)->byGroup($params['groupId'])->exists();
    }
}
