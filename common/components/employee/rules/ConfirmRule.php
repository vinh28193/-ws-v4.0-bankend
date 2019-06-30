<?php

namespace common\components\employee\rules;

use common\helpers\WeshopHelper;
use Yii;
use DateTime;
use DateTimeZone;
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
     *          assigned: [
     *              total:0
     *              amount:0
     *          ],
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
     * @inheritDoc
     */
    public function getActiveSupporter()
    {
        $supporters = $this->employee->getSupporters();
        $confirmPercent = $this->getConfirmPercent();
        $currentAssign = $this->getCurrentAssign();
        $confirmConverted = $confirmPercent['converted'];
        $confirmAgv = $confirmPercent['agv'];
        $totalAssigned = ArrayHelper::getValue($currentAssign, 'total', 0);
        $totalAssigned += 1;
        $results = [];
        foreach (array_keys($supporters) as $id) {
            $ownerConvert = ArrayHelper::getValue($confirmConverted, $id, 0);
            if($confirmAgv === 0){
                $assignRate = 100;
            }else {
                $assignRate = ($ownerConvert / $this->countSupporter() / $confirmAgv);
            }
            $results[$id] = $assignRate;
        }
        $assigned = ArrayHelper::getValue($currentAssign, 'assigned', []);
        $calculator = [];
        foreach ($results as $id => $rate) {
            if (!isset($assigned[$id])) {
                $assigned[$id] = 0;
            }
            //(Lấy số thứ tự đơn hiện tại - tổng số đơn đã được nhận )* tỷ lệ => lớn nhất thì giao
            $calculator[$id] = ($totalAssigned - $assigned[$id]) * $rate;
        }
        arsort($calculator);
        $sMax = WeshopHelper::sortMaxValueArray($calculator);
        // Nếu trọng số bằng nhau thì ưu chỉ số đã nhận thấp hơn
        if (count($sMax) > 1) {
            foreach (array_keys($sMax) as $id) {
                $sMax[$id] = $assigned[$id];
            }
        }

        asort($sMax);
        $assigner = array_keys($sMax);
        $assigner = array_shift($assigner);
        $value = $assigned[$assigner];
        $value += 1;
        $assigned[$assigner] = $value;

        $this->setFileCache('current_assign', [
            'assigned' => $assigned,
            'total' => $totalAssigned
        ]);
        return isset($supporters[$assigner]) ? $supporters[$assigner] : null;
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

//                $converted = [];
//                $array = [
//                    1 => 18,
//                    2 => 24,
//                    3 => 32,
//                    4 => 26
//                ];
//                foreach ($supportIds as $supportId) {
//                    $converted[$supportId] = $array[$supportId];
//                }
                $converted = [];
                $results = $this->loadConfirmPercentBySupporter($supportIds);
                foreach ($supportIds as $id){
                    $converted[$id] = isset($results[$id]) ? $results[$id] : 0;
                }
                $agv = array_sum($converted) / count($converted);
                $confirmPercent = [
                    'expired_at' => $this->getDayExpiredAt(),
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


    public function loadConfirmPercentBySupporter($ids = [])
    {
        $q = new Query();
        $q->from(['o' => Order::tableName()]);
        $q->select([
            'support' => new Expression('`o`.`sale_support_id`'),
            'total' => new Expression('COUNT(`o`.`id`)'),
            'supported' => new Expression('COUNT(IF(`o`.`supported` is not null ,1,null))')
        ]);
        $q->where([
            'AND',
            ['sale_support_id' => $ids],
            ['>=', 'o.new', $this->getFirstDayOfLastWeek()],
            ['<=', 'o.new', $this->getLastDayOfLastWeek()],
        ]);
        $q->groupBy(['support']);
        return ArrayHelper::map($q->all(Order::getDb()), 'support', 'supported');
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

    private function createDateTime($value = 'today', $hour = 23, $minute = 59, $second = 59, $format = 'Y-m-d H:i:s')
    {
        $dateTimeZone = new DateTimeZone(($timeZone = Yii::$app->formatter->timeZone) !== null ? $timeZone : 'UTC');
        $dateTime = new DateTime();
        $dateTime->setTimezone($dateTimeZone);
        $dateTime->modify($value);
        $dateTime->setTime($hour, $minute, $second);
        return $dateTime->format($format);
    }

    public function getFirstDayOfLastWeek()
    {
        return $this->createDateTime('Last Monday - 7 days', 00, 00, 00, 'U');
    }

    public function getLastDayOfLastWeek()
    {
        return $this->createDateTime('Last Sunday', 23, 59, 59, 'U');
    }

    public function getDayExpiredAt(){
        return $this->createDateTime('Next Sunday',23, 59, 59, 'U');
    }
}