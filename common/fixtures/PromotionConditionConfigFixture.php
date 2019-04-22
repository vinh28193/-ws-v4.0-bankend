<?php


namespace common\fixtures;

use yii\test\ActiveFixture;

class PromotionConditionConfigFixture extends ActiveFixture
{
    public $modelClass = 'common\promotion\PromotionConditionConfig';
    public $dataFile = '@common/fixtures/data/data_fixed/promotion_condition_config.php';
}