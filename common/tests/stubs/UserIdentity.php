<?php


namespace common\tests\stubs;


class UserIdentity extends \yii\base\BaseObject implements \yii\web\IdentityInterface, \common\components\UserApiGlobalIdentityInterface
{

    public static $users = [
        '100' => [
            'username' => 'tester',
            'password' => 'mypass',
            'authKey' => 'testerAuthKey'
        ]
    ];

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public static function findByUsername($username)
    {
        // TODO: Implement findByUsername() method.
    }

    public function validatePassword($password)
    {
        // TODO: Implement validatePassword() method.
    }
}