<?php

namespace app\models\forms\auth;

use app\models\User;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public string $password = '';

    public string $password_repeat = '';

    private User $user;

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function attributeLabels(): array
    {
        return [
            'password' => Yii::t('app/model', 'Password'),
            'password_repeat' => Yii::t('app/model', 'Repeat password'),
        ];
    }

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array<string, mixed> $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct(string $token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }
        if (!($user = User::findByPasswordResetToken($token))) {
            throw new InvalidArgumentException('Wrong password reset token.');
        }
        $this->user = $user;
        parent::__construct($config);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, mixed>
     */
    public function rules(): array
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['password', 'match', 'pattern' => '/\d+/', 'message' => Yii::t('app/auth', 'Password must contain at least one number.')],

            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app/auth', 'Passwords do not match.')],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword(): bool
    {
        $user = $this->user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        return $user->save(false);
    }
}
