<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\GroupMember]].
 *
 * @see \app\models\GroupMember
 */
class GroupMemberQuery extends \yii\db\ActiveQuery
{
    public function byGroup(int $groupId): self
    {
        return $this->andWhere(['group_id' => $groupId]);
    }

    public function byUser(int $userId): self
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    public function byCurrentUser(): self
    {
        $userId = \Yii::$app->user->id;
        if (!is_int($userId)) {
            return $this->andWhere('1=0');
        }

        return $this->byUser($userId);
    }
}
