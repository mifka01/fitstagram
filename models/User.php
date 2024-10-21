<?php

namespace app\models;

use app\models\query\UserQuery;
use app\models\TimestampRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;
use yii\web\Session;

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
 * @property Group[] $createdGroups
 * @property Group[] $joinedGroups
 * @property User[] $permittedUsers
 * @property PostVote[] $votes
 * @property Post[] $posts
 * @property Session[] $sessions
 * @property Tag[] $tags
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
    public function rules(): array
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
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app/model', 'ID'),
            'username' => Yii::t('app/model', 'Username'),
            'email' => Yii::t('app/model', 'Email'),
            'password_hash' => Yii::t('app/model', 'Password Hash'),
            'password_reset_token' => Yii::t('app/model', 'Password Reset Token'),
            'verification_token' => Yii::t('app/model', 'Verification Token'),
            'auth_key' => Yii::t('app/model', 'Auth Key'),
            'active' => Yii::t('app/model', 'Active'),
            'deleted' => Yii::t('app/model', 'Deleted'),
            'banned' => Yii::t('app/model', 'Banned'),
            'created_at' => Yii::t('app/model', 'Created At'),
            'updated_at' => Yii::t('app/model', 'Updated At'),
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
            'active' => false,
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

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[CreatedGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJoinedGroups(): ActiveQuery
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])->viaTable('group_user', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedGroups(): ActiveQuery
    {
        return $this->hasMany(Group::class, ['owner_id' => 'id']);
    }

    /**
     * Gets query for [[PermittedUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermittedUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'permitted_user_id'])->viaTable('permitted_user', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[PostVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostVotes(): ActiveQuery
    {
        return $this->hasMany(PostVote::class, ['voted_by' => 'id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts(): ActiveQuery
    {
        return $this->hasMany(Post::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Sessions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSessions(): ActiveQuery
    {
        return $this->hasMany(Session::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['created_by' => 'id']);
    }

    /**
     * {@inheritDoc}
     *
     * @return \app\models\query\UserQuery the active query used by this AR class.
     */
    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }
}
