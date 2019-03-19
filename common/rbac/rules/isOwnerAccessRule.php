<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-05
 * Time: 13:32
 */

namespace common\rbac\rules;

use Yii;

class isOwnerAccessRule extends \yii\rbac\Rule
{

    /**
     * @var string
     */
    public $name = 'isOwnerAccess';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array|string|integer $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($user);
        $role = reset(array_keys($roles));
        if ($role === 'supperAdmin' || $role === 'admin' || strpos($role,'master_') !== false) {
            return true;
        } elseif (is_string($params) || is_numeric($params)) {
            return $params === $user;
        } elseif (isset($params[0])) {
            return $params[0] === $user;
        }elseif (isset($params['create_by']) && ($createBy = $params['create_by'])){
            return $createBy === $user;
        }elseif (isset($params['user_id']) && ($userId = $params['user_id'])){
            return $userId === $user;
        }
        return isset($params['id']) ? $params['id'] == $user : false;
    }
}