<?php


namespace common\components\employee;

use common\components\employee\rules\BaseRule;
use common\models\Order;
use common\models\User;
use Yii;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class Employee
 * @package common\components
 * @link https://docs.google.com/spreadsheets/d/1wgzOoDENLLjXP1JhTLS2ZUFf37YVjWPTe0mtlp6JXNo/edit#gid=0
 *
 * @property-read User[] $supporters
 */
class Employee extends BaseObject
{
    /**
     * @var string
     */
    public $ruleClass = 'common\components\employee\rules\ConfirmRule';

    /**
     * @var array
     */
    public $ruleConfig = [];
    /**
     * @var User[]
     */
    private $_supporters = [];

    /**
     * @var string| yii\rbac\ManagerInterface
     */
    public $authManager = 'authManager';

    public function init()
    {
        parent::init();
        if (!is_string($this->authManager)) {
            $this->authManager = Yii::$app->get($this->authManager);
        }

    }

    /**
     * @return User[]
     */
    public function getSupporters()
    {
        if (empty($this->_supporters)) {
            $this->_supporters = $this->loadSupporterByRoles();

        }
        return $this->_supporters;
    }

    /**
     * @return User[]
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

    /**
     * @param Order[] $orders
     * @param null $customerId
     * @return User[]|null
     */
    public function getAssign($orders, $customerId = null)
    {
        if (!is_array($orders)) {
            $orders = [$orders];
        }
        if ($customerId === null && ($customer = Yii::$app->user->identity) !== null) {
            $customerId = $customer->getId();
        }

        if ($customerId !== null && ($sp = $this->getCustomerSupporter($customerId)) !== false) {
            if (isset($this->getSupporters()[$sp]) && ($usp = $this->getSupporters()[$sp]) !== null) {
                $this->getActiveRule()->markAssigned($sp, $orders);
                return [$usp];
            }
        }
        return $this->getActiveRule()->getActiveSupporters($orders);
    }


    public function getCustomerSupporter($customerId)
    {
        $q = new Query();
        $q->select(['o.sale_support_id']);
        $q->from(['o' => Order::tableName()]);
        $q->where([
            'AND',
            ['is not', 'o.sale_support_id', new Expression('NULL')],
            ['o.customer_id' => $customerId]
        ]);
        $q->limit(1);
        $supporter = $q->column(Order::getDb());
        return !empty($supporter) && isset($supporter[0]) ? $supporter[0] : false;
    }

    /**
     * @return BaseRule
     */
    public function getActiveRule()
    {
        $config = $this->ruleConfig;
        if (!isset($config['class'])) {
            $config['class'] = $this->ruleClass;
        }
        $config['employee'] = $this;
        return Yii::createObject($config);
    }

    protected function isSaleOwner($id){
        $role = $this->authManager->getRolesByUser($id);

    }
}