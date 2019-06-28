<?php

namespace common\components\employee\rules;

use common\helpers\WeshopHelper;
use Yii;
use common\models\Order;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;

class ConfirmRule extends BaseRule
{

    /**
     * @var string
     */
    public $saveFilePath = '@runtime';
    /**
     *
     * ```
     *  {
     *      confirm_percent: {
     *          converted: [
     *              1:25.6
     *              2:9
     *          ],
     *          agv:1000
     *      },
     *      current_assign: {
     *          assigned: [],
     *          total:100
     *      {
     *
     *  }
     * ``` sale_assign.json
     * @var string
     */
    public $saveFileName = 'sale_assign';


    public function init()
    {
        parent::init();
        $this->saveFilePath = Yii::getAlias($this->saveFilePath);
        FileHelper::createDirectory($this->saveFilePath, 0777);
        $this->saveFileName = $this->saveFilePath . '/' . $this->saveFileName . '.json';
    }

    /**
     * @return array
     */
    public function getActiveSupporter()
    {

        $confirmPercent = $this->getConfirmPercent();
        $currentAssign = $this->getCurrentAssign();
        $confirmConverted = $confirmPercent['converted'];
        $confirmAgv = $confirmPercent['agv'];
        $totalAssigned = ArrayHelper::getValue($currentAssign, 'total', 0);
        $totalAssigned += 1;
        $results = [];
        foreach (array_keys($this->employee->getSupporters()) as $id) {
            $ownerConvert = ArrayHelper::getValue($confirmConverted, $id, 100);
            $assignRate = ($ownerConvert / $this->countSupporter() / $confirmAgv);
            $results[$id] = $assignRate;
        }

        $assigned = ArrayHelper::getValue($currentAssign, 'assigned', []);
        $calculator = [];
        foreach ($results as $id => $rate) {
            if (!isset($assigned[$id])) {
                $assigned[$id] = 0;
            }
            $ownerAssign = $assigned[$id];
            $calculator[$id] = (string)(($totalAssigned - $ownerAssign) * $rate);
        }

        asort($calculator);

        var_dump($calculator);
        die;
        $assigner = array_keys($calculator);
        $assigner = array_shift($assigner);


        $assigned[$assigner] += 1;
        var_dump($assigned, $assigner);

        $this->setFileCache('current_assign', [
            'assigned' => $assigned,
            'total' => $totalAssigned
        ]);

        die;
        return $assigner;
    }

    public function countSupporter()
    {
        return count($this->employee->getSupporters());
    }

    private $_confirmPercent = [];

    public function getConfirmPercent()
    {
        if (empty($this->_confirmPercent)) {
            $confirmPercent = $this->getFileCache('confirm_percent', null);
            $supportIds = array_keys($this->employee->getSupporters());
            if ($confirmPercent === null || $confirmPercent['expired_at'] <= time()) {
//                $caculators = $this->loadConfirmPercentBySupporter($supportIds);
                $converted = [];
                foreach ($supportIds as $supportId) {
                    $converted[$supportId] = rand(10, 100);
                }
                $agv = array_sum($converted) / count($converted);
                $confirmPercent = [
                    'expired_at' => Yii::$app->formatter->asTimestamp('now + 7days'),
                    'converted' => $converted,
                    'agv' => $agv
                ];
                $this->setFileCache('confirm_percent', $confirmPercent);
            }
            $this->_confirmPercent = $confirmPercent;

        }
        return $this->_confirmPercent;
    }

    private $_currentAssign = [];

    public function getCurrentAssign()
    {
        if (empty($this->_currentAssign)) {
            $this->_currentAssign = $this->getFileCache('current_assign', []);
        }
        return $this->_currentAssign;
    }

    public function isCanAssign($id)
    {


        var_dump([$id => ['Tỷ lệ phân đơn' => $assignRate, 'So doi da phan' => $ownerAssigned]], ($ownerAssigned / ($totalAssigned - 1)) - $assignRate);

    }

    public function loadConfirmPercentBySupporter($ids = [])
    {
        $q = new Query();
        $q->from(['o' => Order::tableName()]);
        $q->select([
            'support' => new Expression('`o`.`support_id`'),
            'total' => new Expression('COUNT(`o`.`id`)'),
            'supported' => new Expression('COUNT(IF(`o`.`supported` is not null ,1,null))')
        ]);
    }

    public function setFileCache($name, $value)
    {
        $contents = $this->getFileCache(null, []);
        $contents[$name] = $value;
        return file_put_contents($this->saveFileName, Json::encode($contents));
    }

    /**
     * @param null $name
     * @param mixed $default
     * @return mixed|null
     */
    public function getFileCache($name = null, $default = [])
    {
        if (!file_exists($this->saveFileName)) {
            file_put_contents($this->saveFileName, Json::encode([]));
        }
        if (($contents = file_get_contents($this->saveFileName, FILE_USE_INCLUDE_PATH)) !== false) {
            $contents = Json::decode($contents, true);
            return $name !== null ? ArrayHelper::getValue($contents, $name, $default) : $contents;
        }
        return $default;
    }
}