<?php


namespace common\components;


use baibaratsky\yii\google\analytics\MeasurementProtocol;
use common\models\Category;
use common\products\BaseProduct;
use frontend\modules\payment\Payment;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class gaSetting
{
    public static function gaDetail(BaseProduct $product)
    {
        try {
        Yii::info("GA Detail");
        $ga = self::getGa();
        $request = $ga->request();
        $request->setClientId(self::getGaClientId())->setUserId(self::getGaUserId());
        // Then, include the transaction data
        $request->setAffiliation($product->item_name)
            ->setRevenue($product->getLocalizeTotalPrice())
            ->setTax(0)
            ->setShipping(0);
        $productData1 = [
            'sku' => $product->item_id,
            'name' => $product->item_name,
            'brand' => $product->provider ? $product->provider->name : ($product->providers && count($product->providers) > 0 ? $product->providers[0]->name : 'N/A'),
            'category' => $product->type . '/' . ArrayHelper::getValue(Category::getAlias($product->category_id), Yii::$app->storeManager->isVN() ? 'name' : 'originName'),
            'variant' => $product->getSpecific($product->item_id),
            'price' => $product->getLocalizeTotalPrice(),
            'quantity' => 1,
            'coupon_code' => '',
            'position' => strtolower($product->type) == 'ebay' ? 1 : (strtolower($product->type) == 'amazon' ? 2 : 0)
        ];
        $request->addProduct($productData1);
        $request->setProductActionToDetail();
            $request->setTransactionId(1667);
            $request->setItemName($product->item_name);
            $request->setItemCode($product->item_sku);
            $request->setItemCategory($product->category_id);
            $request->setItemPrice($product->getLocalizeTotalPrice());
            $request->setItemQuantity(1);
            $request->setRevenue($product->getLocalizeTotalPrice());
            $request->setProductActionToAdd();
            $request->setProductActionToCheckout();
            $request->setProductActionToClick();
            $request->setProductActionList('Detail product');
            $request->setDocumentPath(Url::base(true) . Url::current());
            $request->setDocumentTitle($product->item_name . " | " . $product->type . " page");
            $request->setEventCategory('Detail');
            $request->setEventAction('view');
            $request->setEventLabel($product->item_name . " | " . $product->type . " page");
            $request->setAsyncRequest(true);
        $request->sendPageview();
            $request->sendTransaction();
            $request->sendItem();
            $request->sendEvent();
        } catch (\Exception $e) {
            Yii::error($e);
        }

    }

    public static function gaCheckout(Payment $payment)
    {
        try {
            Yii::info("GA Checkout");
            $ga = self::getGa();
            $request = $ga->request();
            $request->setClientId(self::getGaClientId())->setUserId(self::getGaUserId());
            // Then, include the transaction data
            $request->setAffiliation("Check out page")
                ->setRevenue($payment->getTotalAmountDisplay())
                ->setTax(0)
                ->setShipping(0);
            foreach ($payment->getOrders() as $order) {
                foreach ($order->products as $product) {
                    $productData1 = [
                        'sku' => strtolower($product->portal) == 'ebay' ? $product->parent_sku : $product->sku,
                        'name' => $product->product_name,
                        'brand' => $product->seller ? $product->seller->seller_name : 'N/A',
                        'category' => $product->portal . '/' . ArrayHelper::getValue(Category::getAlias($product->category_id), Yii::$app->storeManager->isVN() ? 'name' : 'originName'),
                        'variant' => $product->variations,
                        'price' => $product->price_amount_local,
                        'quantity' => $product->quantity_customer,
                        'coupon_code' => '',
                        'position' => strtolower($product->portal) == 'ebay' ? 1 : (strtolower($product->portal) == 'amazon' ? 2 : 0)
                    ];
                    $request->addProduct($productData1);
                }
            }
            $request->setDocumentPath(Url::base(true) . Url::current());
            $request->setDocumentTitle("Check out page");
//            $request->setProductActionToPurchase();
            $request->setTrackingId(1667);
            $request->setAsyncRequest(true);
            $request->sendPageview();
            $request->sendTransaction();
            $request->sendItem();
            $request->setEventCategory('Checkout');
            $request->setEventAction('Purchase');
            $request->sendEvent();
        } catch (\Exception $e) {
            Yii::error($e);
        }

    }

    public static function gaPaymentProcess(Payment $payment)
    {
        try {
            Yii::info("GA payment");
            $ga = self::getGa();
            $request = $ga->request();
            $request->setClientId(self::getGaClientId())->setUserId(self::getGaUserId());
            // Then, include the transaction data
            $request->setAffiliation("Payment processing")
                ->setRevenue($payment->getTotalAmountDisplay())
                ->setTax(0)
                ->setShipping(0);
            foreach ($payment->getOrders() as $order) {
                foreach ($order->products as $product) {
                    $productData1 = [
                        'sku' => strtolower($product->portal) == 'ebay' ? $product->parent_sku : $product->sku,
                        'name' => $product->product_name,
                        'brand' => $product->seller ? $product->seller->seller_name : 'N/A',
                        'category' => $product->portal . '/' . ArrayHelper::getValue(Category::getAlias($product->category_id), Yii::$app->storeManager->isVN() ? 'name' : 'originName'),
                        'variant' => $product->variations,
                        'price' => $product->price_amount_local,
                        'quantity' => $product->quantity_customer,
                        'coupon_code' => '',
                        'position' => strtolower($product->portal) == 'ebay' ? 1 : (strtolower($product->portal) == 'amazon' ? 2 : 0)
                    ];
                    $request->addProduct($productData1);
                }
            }
            $request->setDocumentPath(Url::base(true) . Url::current());
            $request->setDocumentTitle("Payment page");
            $request->setProductActionToPurchase();
            $request->setTrackingId(1667);
            $request->setAsyncRequest(true);
            $request->sendPageview();
            $request->sendTransaction();
            $request->sendItem();
            $request->setEventCategory('Payment');
            $request->setEventAction('processing');
            $request->sendEvent();
        } catch (\Exception $e) {
            Yii::error($e);
        }

    }
    /**
     * @return MeasurementProtocol|mixed
     */
    public static function getGa()
    {
        return Yii::$app->ga;
    }
    public static function generateGaClientId() {

        $ts = round(time() / 1000.0);

        try{
            $uu32 = [];
            $rand = crypt(rand());
        } catch(\Exception $exception) {
            $rand = round(rand() * 2147483647);
        }

        return 'ga.'.implode('.',[$rand, $ts]);

    }
    public static function getGaClientId() {
        if(Yii::$app->user->isGuest){
            if(isset($_COOKIE['ga'])){
                return $_COOKIE['ga'];
            }else{
                $_COOKIE['ga'] = strtoupper(self::generateGaClientId());
                return $_COOKIE['ga'];
            }
        }else{
            $_COOKIE['ga'] = Yii::$app->user->identity->getId() . 'WS' . Yii::$app->user->identity->email;
            return $_COOKIE['ga'];
        }
    }
    public static function getGaUserId() {
        if(Yii::$app->user->isGuest){
            $_COOKIE['ga_u'] = '12345';
            return $_COOKIE['ga_u'];
        }else{
            $_COOKIE['ga_u'] = Yii::$app->user->identity->getId() . 'WS' . Yii::$app->user->identity->email;
            return $_COOKIE['ga_u'];
        }
    }
}