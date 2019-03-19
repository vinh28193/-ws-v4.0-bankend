<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-19
 * Time: 11:19
 */

namespace common\rbac\rules;

interface RuleOwnerAccessInterface
{
    /**
     * parameters for [[CheckAccessInterface::checkAccess()]]
     * @see \yii\web\User::can()
     * @return array
     */
    public function getRuleParams();
}