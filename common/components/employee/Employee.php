<?php


namespace common\components\employee;

use common\models\Order;
use common\models\User;
use Yii;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class Employee
 * @package common\components
 *
 * @property-read User[] $supporters
 */
class Employee extends BaseObject
{

    /**
     * @var User[]
     */
    private $_supporters = [];

    /**
     * @return array|User[]
     */
    public function getSupporters()
    {
        if (empty($this->_supporters)) {
            $this->_supporters = $this->loadSupporterByRoles();
        }
        return $this->_supporters;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function loadSupporterByRoles()
    {
        $authManager = Yii::$app->authManager;
        $saleIds = $authManager->getUserIdsByRole('sale');
        $masterSaleIds = $authManager->getUserIdsByRole('master_sale');
        $ids = array_merge($saleIds, $masterSaleIds);
        $query = User::find();
        $query->indexBy('id');
        $query->select(['id', 'email', 'username']);
        $query->where(['id' => $ids]);
        return $query->all();
    }

    public function calculate()
    {
        $ids = array_keys($this->getSupporters());

    }

    public function createQuery($select = '*', $conditions = [])
    {
        $query = new Query();
        $query->from(['o' => Order::tableName()]);
        $query->select($select);
        $query->where($conditions);
        return $query->all();
    }

    public function ruleConfirmCalculate()
    {

    }
}