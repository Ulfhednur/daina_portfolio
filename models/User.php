<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 * Combined Table and Identity
 *
 * @property    int    $id;
 * @property    string $login;
 * @property    string $user_password;
 * @property    string $auth_key;
 * @property    string $access_token;
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function hashPassword(string $password): string
    {
        return hash('sha256', $password . '_' . env('PASSWORD_SALT'));
    }

    /**
     * @inheritDoc
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'users';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['login', 'user_password'], 'string'],
            [['login', 'user_password'], 'required'],
            [['login'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'user_password' => 'Пароль',
            'user_name' => 'Ф.И.О.',
            'access_token' => 'Токен авторизации',
            'email' => 'E-mail',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): self|null
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): self|null
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by login
     *
     * @param string $login
     * @return static|null
     */
    public static function findByUsername(string $login): self|null
    {
        return self::findOne(['login' => $login]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return $this->user_password === self::hashPassword($password);
    }
}
