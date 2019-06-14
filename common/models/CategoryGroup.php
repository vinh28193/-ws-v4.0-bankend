<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:38 SA
 */

namespace common\models;


use Yii;
use yii\helpers\Json;
use common\models\db_cms\CategoryGroup as DbCmsCategoryGroup;
use common\calculators\CalculatorService;
use common\components\AdditionalFeeInterface;

class CategoryGroup extends DbCmsCategoryGroup
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @param  $target
     * @return float|int
     */
    public function customFeeCalculator(AdditionalFeeInterface $target)
    {
        $start = microtime(true);

        if ($this->rule === null || $this->rule === '') {
            return 0.0;
        }
        $rules = Json::decode($this->rule, true);
        $time = microtime(true) - $start;
        Yii::info('calculate ended (time: ' . sprintf('%.3f', $time) . ' s)', 'CUSTOM FEE INFORMATION');
        return CalculatorService::calculator($rules, $target);
    }
}
