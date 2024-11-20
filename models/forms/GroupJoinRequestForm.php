<?php

namespace app\models\forms;

use app\models\Group;
use app\models\GroupJoinRequest;
use Yii;
use yii\base\Model;

class GroupJoinRequestForm extends Model
{
    public int $group_id;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {
        return [
            [['group_id'], 'required'],
            [['group_id'], 'integer'],
        ];
    }

    public function save() : bool
    {
        $existingRequest = GroupJoinRequest::findOne([
        'group_id' => $this->group_id,
        'created_by' => \Yii::$app->user->id,
        'pending' => true,
        ]);

        if ($existingRequest) {
            return false;
        }
        
        $group = Group::findOne($this->group_id);
        if (!$group) {
            return false;
        }

        try {
            $group->link('groupJoinRequests', new GroupJoinRequest([
            'group_id' => $group->id,
            'created_by' => \Yii::$app->user->id,
            'pending' => true,
            ]));
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return false;
        }

        return true;
    }
}
