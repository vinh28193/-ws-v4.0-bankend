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
            },
            'params' => []
        ], $params);
        return $this->make(BaseRequest::className(), $params);
    }

    // tests
    public function testGetStoreManager()
    {
        $request = $this->make(BaseRequest::className(),[
            'params' => []
        ]);
        $this->specify("check class Name",function ()use ($request){
            verify($request)->isInstanceOf(\common\components\StoreManager::className());
        });
    }

    public function testRules(){

    }

    public function testAttributes(){
        $request = $this->make(BaseRequest::className(),[
            'params' => []
        ]);
        verify($request->attributes())->notNull();
    }
}