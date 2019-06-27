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
use common\components\GetUserIdentityTrait;

/**
 * Class Employee
 * @package common\components
 * @link https://docs.google.com/spreadsheets/d/1wgzOoDENLLjXP1JhTLS2ZUFf37YVjWPTe0mtlp6JXNo/edit#gid=0
 *
 * @property-read User[] $supporters
 */
class Employee extends BaseObject
{
    use GetUserIdentityTrait;
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
     * @return User
     */
    public function getAssign()
    {
        if ($this->checkUserHasSupporter()) {

        }
        return $this->getActiveRule()->getActiveSupporter();
    }

    /**
     * @return BaseRule
     */
    protected function getActiveRule()
    {
        $config = $this->ruleConfig;
        if (!isset($config['class'])) {
            $config['class'] = $this->ruleClass;
        }
        $config['employee'] = $this;
        return Yii::createObject($config);
    }

    public function checkUserHasSupporter()
    {
        if ($this->getUser() === null) {
            return false;
        }
        return false;
    }
}