<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 17:24
 */

namespace api\modules\v1\controllers;

use common\models\User;
use Yii;
use yii\data\ArrayDataProvider;
use api\controllers\BaseApiController;
use yii\helpers\ArrayHelper;

class CartController extends \yii\rest\Controller
{


    public function init()
    {
        Yii::$app->getUser()->setIdentity(User::findOne(1));
        parent::init();
    }

    public function actionIndex()
    {
        $this->getCart()->removeItems();

        $this->getCart()->addItem(['source' => 'ebay', 'seller' => 'cleats_blowout_sports', 'quantity' => 1, 'image' => 'test', 'sku' => '252888606889']);
        $this->getCart()->addItem(['source' => 'ebay', 'seller' => 'cleats_blowout_sports', 'quantity' => 1, 'image' => 'test', 'sku' => '254113708379']);
        $this->getCart()->addItem(['source' => 'ebay', 'seller' => 'mygiftstop', 'quantity' => 1, 'image' => 'test', 'sku' => '332800694983']);
        $this->getCart()->addItem(['source' => 'ebay', 'mygiftstop', 'seller' => 10, 'quantity' => 'test', 'image' => '332800694983']);
        $this->getCart()->addItem(['source' => 'amazon', 'seller' => 'QVVESU9MQUIgLSBTaW5jZSAxOTU4LU5ldy0yNzk=', 'quantity' => 10, 'image' => 'test', 'sku' => 'B07C49F2LD']);
        $this->getCart()->addItem(['source' => 'amazon', 'seller' => 'QW1hem9uLmNvbS1OZXctMjc5', 'quantity' => 10, 'image' => 'test', 'sku' => 'B07C49F2LD']);
        $items = $this->getCart()->getItems();
        var_dump($items);
        die;
//        $this->getCart()->addItem('IF_6C960C53','cleats_blowout_sports',1,'ebay','test','252888606889');
//        $dataProvider = new ArrayDataProvider([
//            'allModels' => $this->getCart()->getItems(),
//        ]);

    }

//    public function actionAddToCart(){
//
//        $this->getCart()->addItem('252888606889','cleats_blowout_sports',1,'ebay','test');
//        return $this->response(true,"ok",$this->getCart()->getItems());
//    }

    /**
     * @return \common\components\cart\CartManager
     */
    protected function getCart()
    {
        return Yii::$app->cart;
    }
}
