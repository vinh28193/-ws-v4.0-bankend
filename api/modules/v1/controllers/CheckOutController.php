<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-08
 * Time: 13:07
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use api\modules\v1\models\CheckOutForm;
use common\components\cart\CartManager;
use common\models\db\Category;
use common\models\Order;
use common\models\Product;
use common\models\ProductFee;
use common\models\Seller;
use Yii;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

class CheckOutController extends BaseApiController
{

    /**
     * @var string|CartManager
     */
    protected $cart;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->cart =Yii::$app->cart;
        $this->post = [
            'paymentProvider' => 1,
            'paymentMethod' => 1
        ];
        $this->cart->removeItems();
        $this->cart->addItem(['source' => 'ebay', 'seller' => 'cleats_blowout_sports', 'quantity' => 1, 'image' => 'test', 'sku' => '252888606889']);
        $this->cart->addItem(['source' => 'ebay', 'seller' => 'cleats_blowout_sports', 'quantity' => 1, 'image' => 'test', 'sku' => '254113708379']);
        $this->cart->addItem(['source' => 'ebay', 'seller' => 'mygiftstop', 'quantity' => 1, 'image' => 'test', 'sku' => '332800694983']);
        $this->cart->addItem(['source' => 'ebay', 'seller' => 'mygiftstop', 'quantity' => 10, 'image' => 'test', 'sku' => '332800694983']);
        $this->cart->addItem(['source' => 'amazon', 'seller' => 'QVVESU9MQUIgLSBTaW5jZSAxOTU4LU5ldy0yNzk=', 'quantity' => 10, 'image' => 'test', 'sku' => 'B07C49F2LD']);
        $this->cart->addItem(['source' => 'amazon', 'seller' => 'QW1hem9uLmNvbS1OZXctMjc5', 'quantity' => 10, 'image' => 'test', 'sku' => 'B07C49F2LD']);

    }

    public function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST']
        ];
    }

    public function actionIndex()
    {

    }

    public function actionCreate()
    {

        $form = new CheckOutForm();
        if (!$form->load($this->post)) {
            return $this->response(false, 'can not load params');
        }
        if (($res = $form->checkout()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        return $this->response(true, 'checkout success', $res);
    }
}
