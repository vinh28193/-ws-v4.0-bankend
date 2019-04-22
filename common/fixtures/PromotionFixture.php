<?php


namespace common\fixtures;

use yii\test\ActiveFixture;

class PromotionFixture extends ActiveFixture
{
    public $modelClass = 'common\promotion\Promotion';
    public $dataFile = '@common/fixtures/data/data_fixed/promotion.php';
}