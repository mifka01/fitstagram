<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Post]].
 *
 * @see \app\models\Post
 */
class PostQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\PostQuery
     */
    public function deleted(bool $deleted = true): self
    {
        return $this->andWhere(['deleted' => $deleted]);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\PostQuery
     */
    public function banned(bool $banned = true): self
    {
        return $this->andWhere(['banned' => $banned]);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\PostQuery
     */
    public function public(bool $public = true): self
    {
        return $this->andWhere(['is_private' => !$public]);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\PostQuery
     */
    public function hasGroup(bool $hasGroup = true): self
    {
        if (!$hasGroup) {
            return $this->andWhere(['group_id' => null]);
        }

        return $this->andWhere(['not', ['group_id' => null]]);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\PostQuery
     */
    public function createdBy(int $id): self
    {
        return $this->andWhere(['created_by' => $id]);
    }
}
