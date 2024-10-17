<?php

namespace app\models;

use app\models\TimestampRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $verification_token
 * @property string $auth_key
 * @property int|null $active
 * @property int|null $deleted
 * @property int|null $banned
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Comment[] $comments
 * @property GroupJoinRequest[] $groupJoinRequests
 * @property GroupUser[] $groupUsers
 * @property Group[] $groups
 * @property Group[] $groups0
 * @property Group[] $groups1
 * @property PermittedUser[] $permittedUsers
 * @property PermittedUser[] $permittedUsers0
 * @property User[] $permittedUsers1
 * @property PostVote[] $postVotes
 * @property Post[] $posts
 * @property Post[] $posts0
 * @property Post[] $posts1
 * @property Session[] $sessions
 * @property Tag[] $tags
 * @property User[] $users
 */
class User extends TimestampRecord implements IdentityInterface
{
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'verification_token', 'auth_key'], 'required'],
            [['active', 'deleted', 'banned'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'email', 'password_hash', 'password_reset_token', 'verification_token', 'auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'active' => Yii::t('app', 'Active'),
            'deleted' => Yii::t('app', 'Deleted'),
            'banned' => Yii::t('app', 'Banned'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function findIdentity($id): ?User
    {
        return static::findOne(['id' => $id, 'active' => true]);
    }

    /**
     * {@inheritDoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): void
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username): ?User
    {
        return static::findOne(['username' => $username, 'active' => true]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'active' => true,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return User|null
     */
    public static function findByVerificationToken($token): ?User
    {
        return static::findOne([
            'verification_token' => $token,
            'active' => true,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId(): string|int
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritDoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }
}
