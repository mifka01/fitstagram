<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class UserUpdateForm extends Model
{
    public string $username = '';

    public string $email = '';

    public ?string $new_password = null;

    public ?string $new_password_repeat = null;

    public bool $show_activity = true;

    private User $user;

    /**
     * Creates a new UserUpdateForm instance.
     *
     * @param User $user
     * @param array<mixed> $config
     */
    public function __construct(User $user, array $config = [])
    {
        $this->user = $user;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->show_activity = $user->show_activity;
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
            [['username', 'email'], 'required'],
            [['username'], 'match', 'pattern' => '/^[\w-]+$/', 'message' => Yii::t('app/model', 'Only alphanumeric characters, hyphens, and underscores are allowed.')],
            [['username'], 'string', 'min' => 3, 'max' => 32],
            [['email'], 'email'],

            ['username', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                $query->andWhere(['!=', 'id', $this->user->id]);
            }, 'message' => Yii::t('app/auth', 'This username is already taken.')],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                $query->andWhere(['!=', 'id', $this->user->id]);
            }, 'message' => Yii::t('app/auth', 'This email address is already taken.')],

            [['new_password'], 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['new_password', 'match', 'pattern' => '/\d+/', 'message' => Yii::t('app/auth', 'Password must contain at least one number.')],
            [['new_password_repeat'], 'compare', 'compareAttribute' => 'new_password',
                'message' => Yii::t('app/auth', 'Passwords do not match.')],
            [['show_activity'], 'boolean'],
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
            'username' => Yii::t('app/model', 'Username'),
            'email' => Yii::t('app/model', 'Email'),
            'new_password' => Yii::t('app/user', 'New Password'),
            'new_password_repeat' => Yii::t('app/user', 'Repeat New Password'),
            'show_activity' => Yii::t('app/model', 'Show Activity'),
        ];
    }

    /**
     * Updates user profile.
     *
     * @return bool whether the profile was updated successfully
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->user;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->show_activity = $this->show_activity;

        if (!empty($this->new_password)) {
            $user->setPassword($this->new_password);
        }

        return $user->save();
    }
}
