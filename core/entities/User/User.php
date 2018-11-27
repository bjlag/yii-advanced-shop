<?php

namespace core\entities\User;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string email_confirm_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property Network $networks
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 10;

    const RELATION_NETWORKS = 'networks';

    /**
     * Ручное создание пользователя из админки.
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function create(string $username, string $email, string $password): self
    {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->status = self::STATUS_ACTIVE;
        $user->created_at = time();
        $user->updated_at = time();
        $user->setPassword($password);
        $user->generateAuthKey();

        return $user;
    }

    /**
     * Обновить данные существующего пользователя.
     * @param string $username
     * @param string $email
     * @param $status
     */
    public function edit(string $username, string $email, $status): void
    {
        $this->username = $username;
        $this->email = $email;
        $this->status = $status;
        $this->updated_at = time();
    }

    /**
     * Запрос на регистрацию пользователя.
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function requestSignup(string $username, string $email, string $password): self
    {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->status = self::STATUS_WAIT;
        $user->created_at = time();
        $user->updated_at = time();
        $user->generateEmailConfirmToken();
        $user->setPassword($password);
        $user->generateAuthKey();

        return $user;
    }

    /**
     * Подтверждение адреса электронной почты и активация пользователя.
     */
    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('Пользователь уже активирован');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->removeEmailConfirmToken();
    }

    /**
     * Запрос на сброс пароля.
     * @return bool
     * @throws \yii\base\Exception
     */
    public function requestPasswordResetToken(): bool
    {
        if (!empty($this->password_reset_token) && static::isPasswordResetTokenValid($this->password_reset_token)) {
            return false;
        }

        $this->generatePasswordResetToken();
        if (!$this->save()) {
            throw new \DomainException('Возникла ошибка при генерации ссылки для восстановления пароля. Попробуйте еще раз.');
        }

        return true;
    }

    /**
     * Сброс пароля.
     * @param string $password
     */
    public function resetPassword(string $password): void
    {
        $this->setPassword($password);
        $this->removePasswordResetToken();
    }

    /**
     * Создание пользователя через социальную сеть.
     * @param string $network
     * @param string $identity
     * @return User
     */
    public static function networkSignup(string $network, string $identity): self
    {
        $user = new static();
        $user->status = self::STATUS_ACTIVE;
        $user->created_at = time();
        $user->updated_at = time();
        $user->generateAuthKey();
        $user->networks = Network::create($network, $identity);

        return $user;
    }

    /**
     * Добавить пользователю социальную сеть.
     * @param string $network
     * @param string $identity
     * @return User
     */
    public function attachNetwork(string $network, string $identity): self
    {
        $networks = $this->networks;
        /** @var Network $current */
        foreach ($networks as $current) {
            if ($current->isFor($network, $identity)) {
                throw new \DomainException('Социальная сеть уже используется');
            }
        }

        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;

        return $this;
    }

    /**
     * Проверяем, что пользователь c неподтвержденным емейлом.
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * Проверяем, что пользователь активный.
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'networks'
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetworks()
    {
        return $this->hasMany(Network::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_WAIT]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен'
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findByEmailConfirmToken($token)
    {
        return static::findOne([
            'email_confirm_token' => $token,
            'status' => self::STATUS_WAIT,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    private static function isPasswordResetTokenValid(string $token): bool
    {
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    private function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws \yii\base\Exception
     */
    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Генерация токена на подтверждение емейла.
     * @throws \yii\base\Exception
     */
    private function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws \yii\base\Exception
     */
    private function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    private function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Удаление токена на подтверждение пароля
     */
    private function removeEmailConfirmToken(): void
    {
        $this->email_confirm_token = null;
    }
}
