<?php


namespace common\components;


use baibaratsky\yii\google\analytics\MeasurementProtocol;
use common\models\Category;
use common\products\BaseProduct;
use Yii;
use yii\helpers\ArrayHelper;

class gaSetting
{
    public static function gaDetail(BaseProduct $product)
    {
        \Yii::$app->ga->request()
            ->setClientId('12345678')
            ->setDocumentPath('/mypage')
            ->sendPageview();
//        try{
        Yii::info("GA Detail");
        $ga = self::getGa();
        $request = $ga->request();
        $request->setClientId(self::getGaClientId())->setUserId(self::getGaUserId());
        // Then, include the transaction data
        $request->setTransactionId('7778956')
            ->setAffiliation('THE ICONIC')
            ->setRevenue(250.0)
            ->setTax(25.0)
            ->setShipping(15.0)
            ->setCouponCode('MY_COUPON');
        $productData1 = [
            'sku' => $product->item_id,
            'name' => $product->item_name,
            'brand' => $product->provider ? $product->provider->name : ($product->providers && count($product->providers) > 0 ? $product->providers[0]->name : 'N/A'),
            'category' => $product->type . '/' . ArrayHelper::getValue(Category::getAlias($product->category_id), Yii::$app->storeManager->isVN() ? 'name' : 'originName'),
            'variant' => $product->getSpecific($product->item_id),
            'price' => $product->getSellPrice(),
            'quantity' => 1,
            'coupon_code' => '',
            'position' => strtolower($product->type) == 'ebay' ? 1 : (strtolower($product->type) == 'amazon' ? 2 : 0)
        ];
        $request->addProduct($productData1);
        $request->setProductActionToDetail();
        $request->sendPageview();
        $request->setEventCategory('Detail')
            ->setEventAction('view')
                ->setAsyncRequest(true)//  'asyncMode' => true,
            ->sendEvent();
//        }catch (\Exception $e){
//            Yii::error($e);
//        }

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