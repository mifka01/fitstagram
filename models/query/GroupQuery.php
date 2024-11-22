<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Group]].
 *
 * @see \app\models\Group
 */
class GroupQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\GroupQuery
     */
    public function active(bool $active = true): self
    {
        return $this->andWhere(['active' => $active]);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\GroupQuery
     */
    public function deleted(bool $deleted = true): self
    {
        return $this->andWhere(['deleted' => $deleted]);
    }
}
