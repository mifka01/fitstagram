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
}
