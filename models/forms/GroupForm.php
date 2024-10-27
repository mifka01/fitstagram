<?php

namespace app\models\forms;

use app\models\Group;
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

    public bool $active = false;

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

        $group = new Group();
        $group->name = $this->name;
        $group->description = $this->description;
        $group->active = (int)$this->active;
        $group->owner_id = $user->id;

        return $group->save();
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
