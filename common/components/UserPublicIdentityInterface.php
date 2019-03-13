<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-13
 * Time: 17:33
 */

namespace common\components;


interface UserPublicIdentityInterface
{

    /**
     * @return array
     */
    public function getPublicIdentity();
}