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
     * @return User[]
     */
    public function getSupporters()
    {
        if (empty($this->_supporters)) {
            $this->_supporters = $this->loadSupporterByRoles();
//            $s = [];
//            for ($i = 1;$i <= 4;$i++){
//                $s[$i] = [
//                    'name' => "Sale $i"
//                ];
//            }
//            $this->_supporters = $s;

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
        return $this->getActiveRule()->getActiveSupporter();
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
}