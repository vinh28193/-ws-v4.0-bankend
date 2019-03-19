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
     *   class Order implements RuleOwnerAccessInterface{
     *      public function getRuleParams(){
                return $this->saleAssignId;
     *      }
     *  }
     *  in controller
     *  $order = Order::findOne(1);
     *  if(this->can('Permission',$order)){
     *      // action
     *  }
     *  --
     * rule parameters for [[CheckAccessInterface::checkAccess()]]
     * @see \yii\web\User::can()
     * @return array|string|integer
     */
    public function getRuleParams();
}