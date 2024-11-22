<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    public function active(bool $active = true): UserQuery
    {
        return $this->andWhere(['active' => $active]);
    }

    public function deleted(bool $deleted = true): UserQuery
    {
        return $this->andWhere(['deleted' => $deleted]);
    }

    public function banned(bool $deleted = true): UserQuery
    {
        return $this->andWhere(['banned' => $deleted]);
    }
}
