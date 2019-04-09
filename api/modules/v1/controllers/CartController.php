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

    public function actionIndex(){
        $this->getCart()->removeItems();

        $this->getCart()->addItem('ebay','cleats_blowout_sports',1,'test','252888606889');
        var_dump($this->getCart()->getItem('ebay','cleats_blowout_sports','254113708379'));die;
        $this->getCart()->addItem('ebay','cleats_blowout_sports',1,'test','254113708379');
        $items = $this->getCart()->getItems();
        var_dump(1);die;
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
    protected function getCart(){
        return Yii::$app->cart;
    }
}
