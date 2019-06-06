<?php


namespace frontend\controllers;

use common\components\cart\CartHelper;
use common\components\cart\CartManager;
use common\helpers\WeshopHelper;
use common\models\User;
use frontend\modules\payment\providers\mcpay\McPayProvider;
use Yii;
use common\components\cart\storage\MongodbCartStorage;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\alepay\AlepayClient;
use yii\helpers\ArrayHelper;

class TestController extends FrontendController
{

    public function actionTestMe()
    {
        echo PaymentService::createReturnUrl(42);
        die;
    }

    public function actionTestRsa()
    {

    }

    public function actionAlepay()
    {
        $alepay = new AlepayClient();
        echo "<pre>";
        var_dump($alepay->getInstallmentInfo(10000000.00, 'VND'));
    }

    public function actionTestCart()
    {
        /** @var  $cartManager CartManager */

        $cartManager = Yii::$app->cart;

        $cartManager->addItem('shopping', [
            'source' => 'ebay',
            'sku' => '100-99800902-NRC',
            'id' => '163586118957',
            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
            'quantity' => 2,
            'image' => 'https://i.ebayimg.com/00/s/MTQwMFgxNDAw/z/fCkAAOSwuN9cgrz3/$_1.JPG'
        ]);
//
//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => '100-99000000-02',
//            'id' => '163189059666',
//            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
//            'quantity' => 1,
//            'image' => 'https://d3d71ba2asa5oz.cloudfront.net/12022392/images/elite_65t_earbuds_rgb_72dpi.jpg'
//        ]);
//
//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => '204151',
//            'id' => '163655512954',
//            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
//            'quantity' => 1,
//            'image' => 'https://i.ebayimg.com/00/s/MTQwMFgxNDAw/z/Qr0AAOSwuCVcuKWl/$_1.JPG'
//        ]);
//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => '100-99600900-02',
//            'id' => '163314595720',
//            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
//            'quantity' => 1,
//            'image' => 'https://i.ebayimg.com/00/s/MTQwMFgxNDAw/z/kGwAAOSwnFpbwy9H/$_1.JPG'
//        ]);
//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => 'YC1060-I;0',
//            'id' => '232738139862',
//            'sellerId' => 'dGVtcG9yZXgtaW50ZXJuYXRpb25hbC0t',
//            'quantity' => 1,
//            'image' => 'https://i.ebayimg.com/00/s/MTYwMFgxNjAw/z/3hAAAOSwQ~ha1u4Q/$_1.JPG'
//        ]);
//        var_dump($cartManager->filterItem('shopping', ['source' => 'ebay', 'seller' => 'Y2hvb3Nlc21hcnQtLQ==']));

//        $item1 = $cartManager->updateItem('shopping', '5ced1365e419ac1fb80057b9', ['id' => '163586118957', 'sku' => '100-99800902-NRC'], ['quantity' => 1]);
//        $cartManager->removeItem('shopping', '5cee2e6ce419ac05a00007eb', ['id' => '163655512954', 'sku' => '204151']);
        $item2 = $cartManager->getItem('shopping', '5cef4645e419ac46000075a0');

        var_dump($item2);
        die;
    }

    public function actionTime()
    {
        $formater = Yii::$app->formatter;
        $dateTime = new \DateTime('now');
        $dateTime->setTime(23, 59, 59, 59);
        var_dump($formater->asDatetime($dateTime));
        die;
    }

    public function actionTestCount()
    {
        $storage = new MongodbCartStorage();
        $authManager = Yii::$app->authManager;
        $saleIds = $authManager->getUserIdsByRole('sale');
        $masterSaleIds = $authManager->getUserIdsByRole('master_sale');
        $supporters = User::find()->indexBy('id')->select(['id', 'email'])->where(['or', ['id' => $saleIds], ['id' => $masterSaleIds]])->all();

        $ids = array_keys($supporters);
        $calculateToday = ArrayHelper::map($storage->calculateSupported($ids), '_id', function ($elem) {
            return ['count' => $elem['count'], 'price' => $elem['price']];
        });

        $countData = [];
        foreach ($ids as $id) {
            $c = 0;
            if (isset($calculateToday[$id]) && ($forSupport = $calculateToday[$id]) !== null && !empty($forSupport) && isset($forSupport['count'])) {
                $c = $forSupport['count'];
            }
            $countData[$id] = $c;
        }
        asort($countData);

        $sQMin = WeshopHelper::sortMinValueArray($countData);

        $priceResult = [];

        foreach ($sQMin as $id => $val) {
            $p = 0;
            if (isset($calculateToday[$id]) && ($forSupport = $calculateToday[$id]) !== null && !empty($forSupport) && isset($forSupport['price'])) {
                $p = $forSupport['price'];
            }
            $priceResult[$id] = $p;
        }
        $priceResult = array_keys($priceResult);
        $id = array_shift($priceResult);
        if (($assigner = ArrayHelper::getValue($supporters, $id)) === null) {
            $assigner = array_shift($supporters);
        }
        var_dump($assigner);
        die;
    }

    public function actionI18n()
    {
        echo Yii::t('test', 'Hello World');
        $s = Yii::$app->i18n->getMessageSource('frontend');
        var_dump($s->loadMessages('frontend', 'vi'));
        die;
    }
    public function actionCreate(){
        $provice = new McPayProvider();
        $provice->amount = '20000';
        $provice->orderId = 'asdasdasd';
        var_dump($provice->createCheckOutUrl());die;
    }
}