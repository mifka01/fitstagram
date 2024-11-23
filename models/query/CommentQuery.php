<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Comment]].
 *
 * @see \app\models\Comment
 */
class CommentQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\CommentQuery
     */
    public function deleted(bool $deleted = true): self
    {
        return $this->andWhere(['deleted' => $deleted]);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\CommentQuery
     */
    public function banned(bool $banned = true): self
    {
        return $this->andWhere(['banned' => $banned]);
    }
}
