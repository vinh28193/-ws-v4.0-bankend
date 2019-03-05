<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-05
 * Time: 13:32
 */

namespace common\rbac\rules;

class isOwnRule extends \yii\rbac\Rule
{

    public $name = 'isOwn';

    public function execute($user, $item, $params)
    {
        return isset($params['id']) ? $params['id'] == $user : false;
    }
}