<?php


namespace frontend\controllers;

use common\components\cart\CartHelper;
use common\components\cart\CartManager;
use Yii;
use common\components\cart\storage\MongodbCartStorage;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\alepay\AlepayClient;

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

//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => '100-99800902-NRC',
//            'id' => '163586118957',
//            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
//            'quantity' => 2,
//            'image' => 'https://i.ebayimg.com/00/s/MTQwMFgxNDAw/z/fCkAAOSwuN9cgrz3/$_1.JPG'
//        ]);
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
        $cartManager->addItem('shopping', [
            'source' => 'ebay',
            'sku' => '100-99600900-02',
            'id' => '163314595720',
            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
            'quantity' => 1,
            'image' => 'https://i.ebayimg.com/00/s/MTQwMFgxNDAw/z/kGwAAOSwnFpbwy9H/$_1.JPG'
        ]);
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
        $item2 = $cartManager->getItem('shopping', '5cee4fdde419ac05a00007f4');

        var_dump($item2);
        die;
    }
}