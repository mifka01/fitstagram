<?php

namespace app\models\forms\auth;

use app\models\User;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class VerifyEmailForm extends Model
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var User
     */
    private $user;

    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @param array<string, mixed> $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, array $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Verify email token cannot be blank.');
        }

        if (!($user = User::findByVerificationToken($token))) {
            throw new InvalidArgumentException('Wrong verify email token.');
        }

        $this->user = $user;
        parent::__construct($config);
    }

    /**
     * Verify email
     *
     * @return User|null the saved model or null if saving fails
     */
    public function verifyEmail(): ?User
    {
        $user = $this->user;
        $user->active = (int) true;
        return $user->save(false) ? $user : null;
    }
}
