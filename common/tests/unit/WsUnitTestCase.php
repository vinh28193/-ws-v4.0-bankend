<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-02
 * Time: 10:21
 */

namespace common\tests\unit;

use yii\helpers\ArrayHelper;

class WsUnitTestCase extends Codeception\Test\Unit
{
    use \Codeception\Specify;

    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [];
    }


    public function _setUp()
    {
        parent::_setUp();
    }

    public function _before()
    {
//        $fixtures = ArrayHelper::merge($this->defaultFixtures(),$this->fixtures());
//        $this->tester->haveFixtures($fixtures);
//        $this->beforeSpecify(function (){
//
//        });
        parent::_before();
    }

    protected function defaultFixtures(){
        return [
            'store' => [
                'class' => \common\fixtures\StoreFixture::className(),
            ],
            'storeAdditionalFee' => [
                'class' => \common\fixtures\StoreAdditionalFeeFixture::className()
            ]
        ];
    }
}
