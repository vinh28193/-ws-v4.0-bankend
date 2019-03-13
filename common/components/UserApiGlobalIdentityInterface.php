<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-13
 * Time: 16:42
 */

namespace common\components;


interface UserApiGlobalIdentityInterface
{

    public static function findByUsername($username);

    public function validatePassword($password);
}