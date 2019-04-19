<?php


namespace common\fixtures;

use yii\test\ActiveFixture;

class PromotionConditionFixture extends ActiveFixture
{
    public $modelClass = 'common\promotion\PromotionCondition';
    public $depends = [
        'common\fixtures\PromotionConditionConfigFixture',
    ];
    public $dataFile = '@common/fixtures/data/data_fixed/promotion_condition.php';
}