<?php

namespace app\models\forms;

use app\models\Group;
use app\models\GroupMember;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * GroupForm is the model behind the group form.
 */
class GroupForm extends Model
{
    public string $name = '';

    public string $description = '';

    public bool $active = true;

    public ?Group $group = null;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 512],
            [['active'], 'boolean'],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('app/group', 'Name'),
            'description' => Yii::t('app/group', 'Description'),
            'active' => Yii::t('app/group', 'Active'),
        ];
    }

    /**
     * Save group
     *
     * @return bool
     */
    public function save(): bool
    {
        $user = $this->getCurrentUser();

        if ($user === null) {
            return false;
        }

        // Use the existing group if available, otherwise create a new one
        $this->group = $this->group ?? new Group();
    
        $this->group->name = $this->name;
        $this->group->description = $this->description;
        $this->group->active = (int)$this->active;

        // Only set the owner_id if it's a new group
        if ($this->group->isNewRecord) {
            $this->group->owner_id = $user->id;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Save the group first
            if (!$this->group->save()) {
                $transaction->rollBack();
                return false;
            }
            $groupMember = new GroupMember();
            $groupMember->group_id = $this->group->id;
            $groupMember->user_id = $user->id;
           
            if (!$groupMember->save()) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function getGroup(): Group
    {
        if ($this->group === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'Group not found.'));
        }
        return $this->group;
    }

    /**
     * Get the current user.
     *
     * @return User|null
     * @throws NotFoundHttpException
     */
    private function getCurrentUser(): User|null
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        $user = User::findOne(Yii::$app->user->id);
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }
        return $user;
    }
}
