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
    public function public(bool $public = true): self
    {
        return $this->andWhere(['is_private' => !$public]);
    }
}
