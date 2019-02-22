<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 15:24
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class ProductFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Product';
    public $depends = [
        'common\fixtures\OrderFixture',
        'common\fixtures\SellerFixture',
        'common\fixtures\CategoryFixture',
        'common\fixtures\CategoryCustomPolicyFixture',
    ];
}