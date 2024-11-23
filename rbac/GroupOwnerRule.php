<?php

namespace app\rbac;

use app\models\Group;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Checks if group.owner_id matches user passed via params
 */
class GroupOwnerRule extends Rule
{
    public $name = 'isGroupOwner';

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

        return Group::find()->deleted(false)->banned(false)->andWhere(['id' => $params['groupId']])->andWhere(['owner_id' => $user])->exists();
    }
}
