<?php namespace common\tests\products;

use common\tests\UnitTestCase;
use common\products\BaseProduct;
use yii\helpers\ArrayHelper;

class BaseProductTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return array_merge(parent::_fixtures(), [
            'store_additional_fee' => [
                'class' => 'common\tests\fixtures\StoreAdditionalFeeFixture'
            ],
            'category' => [
                'class' => 'common\fixtures\CategoryFixture'
            ],
        ]);
    }

    public function mockProduct($params = [])
    {
        $category = $this->grabFixture('category', 1);
        $params = ArrayHelper::merge([
            'getCustomCategory' => $category
        ], $params);
        return $this->make(BaseProduct::className(), $params);
    }
//    public function testSetVariationOptions()
//    {
//
//    }
//
//    public function testSetVariationMapping()
//    {
//    }
//
//    public function testSetTechnicalSpecific()
//    {
//    }
//
//    public function testSetRelateProduct()
//    {
//    }
//
//    public function testSetImages()
//    {
//    }
//
//    public function testCheckOutOfStock()
//    {
//    }
//
//    public function testGetSalePercent()
//    {
//    }
//
//    public function testGetSpecific()
//    {
//    }
//
//    public function testUpdateBySku()
//    {
//    }
//
//    public function testGetSeller()
//    {
//    }
//
//    public function testUpdateBySeller()
//    {
//    }

    public function testInit()
    {
        $params = [
            'getExchangeRate' => 2300,
            'getSellPrice' => 200,
            'us_tax_rate' => 12,
            'shipping_fee' => 2,
            'getShippingQuantity' => 1,
            'getShippingWeight' => 1,
            'getItemType' => 'ebay'
        ];

        $this->specify('check have data', function () use ($params) {
            $baseProduct = $this->mockProduct($params);
            $baseProduct->init();
            verify($baseProduct->getAdditionalFees()->storeAdditionalFee)->notNull();
            verify($baseProduct->getAdditionalFees()->keys())->notNull();
        });

        $this->specify('product_price_origin', function () use ($params) {
            $baseProduct = $this->mockProduct($params);
            $baseProduct->init();
            $value = $baseProduct->getAdditionalFees()->get('product_price_origin');
            verify($value)->notNull();
            verify($value['amount'])->equals(200);
            verify($value['local_amount'])->equals(200 * $baseProduct->getExchangeRate());
        });

        $this->specify('tax_fee_origin', function () use ($params) {
            $baseProduct = $this->mockProduct($params);
            $baseProduct->init();
            $value = $baseProduct->getAdditionalFees()->get('tax_fee_origin');
            verify($value)->notNull();
            verify($value['amount'])->equals(12);
            verify($value['local_amount'])->equals(12 * $baseProduct->getExchangeRate());
        });

        $this->specify('origin_shipping_fee', function () use ($params) {
            $baseProduct = $this->mockProduct($params);
            $baseProduct->init();
            $value = $baseProduct->getAdditionalFees()->get('origin_shipping_fee');
            verify($value)->notNull();
            verify($value['amount'])->equals(2);
            verify($value['local_amount'])->equals(2 * $baseProduct->getExchangeRate());
        });

        $this->specify('weshop_fee', function () use ($params) {
            $baseProduct = $this->mockProduct($params);
            $baseProduct->init();
            $value = $baseProduct->getAdditionalFees()->get('weshop_fee');
            verify($value)->notNull();
            verify($value['amount'])->equals($baseProduct->getTotalOriginPrice() * 0.12);
            verify($value['local_amount'])->equals($baseProduct->getTotalOriginPrice() * 0.12 * $baseProduct->getExchangeRate());

            $this->specify("change product type amazon 10 %", function () use ($params) {
                $params['getItemType'] = 'amazon';
                $baseProduct = $this->mockProduct($params);
                $baseProduct->init();
                $value = $baseProduct->getAdditionalFees()->get('weshop_fee');
                verify($value)->notNull();
                verify($value['amount'])->equals($baseProduct->getTotalOriginPrice() * 0.1);
                verify($value['local_amount'])->equals($baseProduct->getTotalOriginPrice() * 0.1 * $baseProduct->getExchangeRate());
            });

            $this->specify("change price to case 1000 to 1500 fee 8.5%", function () use ($params) {
                $params['getSellPrice'] = 1000; // dont for got ustax and us ship
                $baseProduct = $this->mockProduct($params);
                $baseProduct->init();
                $value = $baseProduct->getAdditionalFees()->get('weshop_fee');
                verify($value)->notNull();
                verify($value['amount'])->equals($baseProduct->getTotalOriginPrice() * 0.085);
                verify($value['local_amount'])->equals($baseProduct->getTotalOriginPrice() * 0.085 * $baseProduct->getExchangeRate());
            });
        });

        $this->specify('intl_shipping_fee', function () use ($params) {
            $baseProduct = $this->mockProduct($params);
            $baseProduct->init();
            $value = $baseProduct->getAdditionalFees()->get('intl_shipping_fee');
            verify($value)->notNull();
            verify($value['amount'])->equals(10);
            verify($value['local_amount'])->equals(10 * $baseProduct->getExchangeRate());

            $this->specify("change weight 20kg", function () use ($params) {
                $params['getShippingWeight'] = 20; // dont for got ustax and us ship
                $baseProduct = $this->mockProduct($params);
                $baseProduct->init();
                $value = $baseProduct->getAdditionalFees()->get('intl_shipping_fee');
                verify($value)->notNull();
                verify($value['amount'])->equals(10 * 20);
                verify($value['local_amount'])->equals(10 * 20 * $baseProduct->getExchangeRate());
            });
        });

        $this->specify('custom_fee', function () use ($params) {
            $baseProduct = $this->mockProduct($params);
            $baseProduct->init();
            $value = $baseProduct->getAdditionalFees()->get('custom_fee');
            verify($value)->notNull();

            $oldValue = $value;

            $this->specify("change params", function () use ($params,$oldValue) {
                $params['getSellPrice'] = 20; // dont for got ustax and us ship
                $baseProduct = $this->mockProduct($params);
                $baseProduct->init();
                $value = $baseProduct->getAdditionalFees()->get('custom_fee');
                verify($value)->notNull();
                verify($value['amount'])->notEquals($oldValue['amount']);
                verify($value['local_amount'])->notEquals($oldValue['local_amount']);
            });
        });
    }

//    public function testGetSellPrice()
//    {
//    }
//
//    public function testGetItemType()
//    {
//    }
//
//    public function testGetTotalOriginPrice()
//    {
//    }
//
//    public function testGetCustomCategory()
//    {
//    }
//
//    public function testGetSiteMapping()
//    {
//    }
//
//    public function testGetShippingWeight()
//    {
//    }
//
//    public function testRoundShippingWeight()
//    {
//    }
//
//    public function testGetShippingQuantity()
//    {
//    }
//
//    public function testSetIsForWholeSale()
//    {
//    }
//
//    public function testGetIsNew()
//    {
//    }
//
//    public function testGetExchangeRate()
//    {
//    }
//
//    public function testGetLocalizeTotalPrice()
//    {
//    }
//
//    public function testGetLocalizeTotalStartPrice()
//    {
//    }
//
//    public function testIsEmpty()
//    {
//    }
}