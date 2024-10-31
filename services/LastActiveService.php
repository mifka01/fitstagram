<?php

namespace app\services;

use Yii;

use yii\base\Component;

/**
 * LastActiveService component
 */
class LastActiveService extends Component
{
    /**
     * Get the last active timestamp for a user.
     *
     * @param int $userId
     * @return ?int
     */
    public function getLastActiveAt($userId): ?int
    {
        $command = Yii::$app->db->createCommand('SELECT last_write FROM session WHERE user_id = :id ORDER BY expire DESC LIMIT 1', [':id' => $userId]);

        $last_write = $command->queryScalar();

        if ($last_write === false) {
            return null;
        }

        if (is_string($last_write)) {
            return null;
        }

        return $last_write;
    }
}
