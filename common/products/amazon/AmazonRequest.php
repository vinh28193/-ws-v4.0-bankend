<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 20:25
 */

namespace common\products\amazon;

use common\products\BaseRequest;
use yii\helpers\ArrayHelper;

class AmazonRequest extends BaseRequest
{
    const SCENARIO_SEARCH = 'search';
    const SCENARIO_LOOKUP = 'lookup';

    public $store = AmazonProduct::STORE_US;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['store', 'safe'],
        ]);
    }

    public function params()
    {
        return ['store' => $this->store];
    }
}