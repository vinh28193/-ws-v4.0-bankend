<?php


namespace common\components;

use Yii;
use common\models\User;

/**
 * Class GetUserIdentityTrait
 * @package common\components
 * @property-read null|User $user
 */
trait GetUserIdentityTrait
{
    /**
     * @var null|User
     */
    private $_user = null;

    /**
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null && ($user = Yii::$app->user->identity) !== null) {
            $this->_user = $user;
        }
        return $this->_user;
    }

}