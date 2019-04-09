<?php namespace common\tests\products;

use common\models\Store;
use common\tests\UnitTestCase;
use common\products\BaseRequest;
use yii\helpers\ArrayHelper;


class BaseRequestTest extends UnitTestCase
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    private function mockRequest($params = [])
    {
        $storeId = 1;
        if (isset($params['storeId'])) {
            $storeId = $params['storeId'];
            unset($params['storeId']);
        }
        $params = ArrayHelper::merge([
            'getStoreManager' => function () use ($storeId) {
                $store = new Store(['id' => $storeId]);
                $manager = $this->make('common\components\StoreManager', [
                    'getStore' => $store
                ]);
                return $manager;
            }
        ], $params);
        return $this->make(BaseRequest::className(), $params);
    }

    // tests
    public function testSomeFeature()
    {

    }
}