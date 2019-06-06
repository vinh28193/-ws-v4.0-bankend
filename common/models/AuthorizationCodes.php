<?php

namespace common\models;

use Yii;
use common\models\db\AuthorizationCodes as DbAuthorizationCodes;

class AuthorizationCodes extends DbAuthorizationCodes
{
    public function isValid()
    {
        if (!$this->expires_at >= time()) {
            return false;
        }
        return true;
    }
}
