<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\GroupJoinRequest]].
 *
 * @see \app\models\GroupJoinRequest
 */
class GroupJoinRequestQuery extends \yii\db\ActiveQuery
{
    public function pending(bool $pending = true): self
    {
        return $this->andWhere(['pending' => $pending]);
    }

    public function declined(bool $declined = true): self
    {
        return $this->andWhere(['declined' => $declined]);
    }

    public function accepted(bool $accepted = true): self
    {
        return $this->andWhere(['accepted' => $accepted]);
    }

    public function forGroup(int $groupId): self
    {
        return $this->andWhere(['group_id' => $groupId]);
    }

    public function byUser(int $userId): self
    {
        return $this->andWhere(['created_by' => $userId]);
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
