<?php

namespace app\models\forms;

use app\models\PermittedUser;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class PermittedUserForm extends Model
{
    public ?int $id = null;

    /**
     * @return array<int, array<mixed>>
     */
    public function rules(): array
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['id'], 'exist', 'targetClass' => PermittedUser::class, 'targetAttribute' => 'permitted_user_id'],
        ];
    }

    public function revokePermittedUser(): bool
    {

        if (!$this->validate()) {
            return false;
        }

        $user = $this->getCurrentUser();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $permittedUser = PermittedUser::findOne([
                'user_id' => $user->id,
                'permitted_user_id' => $this->id,
            ]);
            if (!$permittedUser) {
                $this->addError('user_id', 'Permitted User not found.');
                return false;
            }

            if (!$permittedUser->delete()) {
                $this->addErrors($permittedUser->errors);
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('Revoke Permitted User Error: ' . $e->getMessage());
            $this->addError('user_id', 'An error occurred while revoking the permitted user.');
            return false;
        }
    }

    public function addPermittedUser(): bool
    {

        if (!$this->validate()) {
            return false;
        }

        $user = $this->getCurrentUser();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $permittedUser = PermittedUser::findOne([
                'user_id' => $user->id,
                'permitted_user_id' => $this->id,
            ]);
            if ($permittedUser) {
                $this->addError('user_id', 'Permitted User already exists.');
                return false;
            }
            $permittedUser = new PermittedUser([
                'user_id' => $user->id,
                'permitted_user_id' => $this->id,
            ]);

            if (!$permittedUser->save()) {
                $this->addErrors($permittedUser->errors);
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('Revoke Permitted User Error: ' . $e->getMessage());
            $this->addError('user_id', 'An error occurred while revoking the permitted user.');
            return false;
        }
    }

    /**
     * Get the current user.
     *
     * @return User
     * @throws NotFoundHttpException
     */
    private function getCurrentUser(): User
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }

        $user = User::findOne(Yii::$app->user->id);
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }
        return $user;
    }
}
