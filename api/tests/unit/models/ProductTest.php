<?php

namespace api\unit\models;

use common\models\Product;
use Codeception\Test\Unit;
use tests\fixtures\ProductFixture;

class ProductTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _before()
    {
//        $this->tester->haveFixtures([
//            'product' => [
//                'class' => ProductFixture::className(),
//                'dataFile' => codecept_data_dir() . 'product.php'
//            ]
//        ]);
    }

    public function testValidateEmpty()
    {
        $model = new Product();

        expect('model should not validate', $model->validate())->false();
    }

    public function testValidateCorrect()
    {
        $model = new Product();
        $model->attributes = [
            'order_id' => 9,
            'seller_id' => 2,
            'portal' => 'OTHER',
            'sku' => '3925f434d1963f204474b000692100f2',
            'product_name' => 'Dormouse shall!\' they both cried. \'Wake up, Alice dear!\' said her sister; \'Why, what are YOUR shoes done with?\' said the Duchess; \'and that\'s the jury, and the sounds will take care of the same.',
            'parent_sku' => '8fdec8073dc744db79afdc643203e2f7',
            'link_img' => 'https://lorempixel.com/640/480/?87017',
            'link_origin' => 'http://bien.org/',
            'category_id' => 2,
            'custom_category_id' => 1,
            'price_amount_origin' => 2,
            'quantity_customer' => 1,
            'price_amount_local' => 23500,
            'total_price_amount_local' => 70500,
            'quantity_purchase' => 0,
            'quantity_inspect' => 0,
            'variations' => '',
            'variation_id' => '',
            'note_by_customer' => 'Let me see: four.',
            'total_weight_temporary' => 0.5,
            'created_at' => 1439012901,
            'updated_at' => 385493892,
            'remove' => 0,
        ];

        expect('model should validate', $model->validate())->true();
    }

    public function testSave()
    {
        $model = new Product();
        $model->attributes = [
            'order_id' => 9,
            'seller_id' => 2,
            'portal' => 'OTHER',
            'sku' => '3925f434d1963f204474b000692100f2',
            'product_name' => 'Dormouse shall!\' they both cried. \'Wake up, Alice dear!\' said her sister; \'Why, what are YOUR shoes done with?\' said the Duchess; \'and that\'s the jury, and the sounds will take care of the same.',
            'parent_sku' => '8fdec8073dc744db79afdc643203e2f7',
            'link_img' => 'https://lorempixel.com/640/480/?87017',
            'link_origin' => 'http://bien.org/',
            'category_id' => 2,
            'custom_category_id' => 1,
            'price_amount_origin' => 2,
            'quantity_customer' => 1,
            'price_amount_local' => 23500,
            'total_price_amount_local' => 70500,
            'quantity_purchase' => 0,
            'quantity_inspect' => 0,
            'variations' => '',
            'variation_id' => '',
            'note_by_customer' => 'Let me see: four.',
            'total_weight_temporary' => 0.5,
            'created_at' => 1439012901,
            'updated_at' => 385493892,
            'remove' => 0,
        ];

        expect('model should save', $model->save())->true();

        expect('note_by_customer is correct', $model->note_by_customer)->equals('Let me see: four.');
        expect('total_price_amount_local is correct', $model->total_price_amount_local)->equals(70500);
        expect('category_id is draft', $model->category_id)->equals(2);
        expect('id is generated', $model->id)->notEmpty();
        expect('created_at is generated', $model->created_at)->notEmpty();
        expect('updated_at is generated', $model->updated_at)->notEmpty();
    }


}
