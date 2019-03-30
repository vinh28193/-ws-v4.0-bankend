<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 11:14
 */

namespace common\products\ebay;


class EbayDetailRequest extends \common\products\BaseRequest
{

    /**
     * @return string
     */
    public function getFullUrl(){
        $ebay_api = isset(\Yii::$app->params['ebay-api']) ? (\Yii::$app->params['ebay-api']) : 'https://ebay-api-wshopx-v3.weshop.com.vn/v3';
        return $ebay_api.'/product';
        //https://ebay-api-wshopx-v3.weshop.com.vn/v3/product?id=372500539367
    }
    /**
     * @param \yii\httpclient\Request $httpRequest
     * @return \yii\httpclient\Request
     */
    public function buildHttpRequest($httpRequest){
        $url = $this->getFullUrl();
        $url .= "?id={$this->buildParams()}";
        return $httpRequest->setFullUrl($url)->setFormat('json')->setMethod('POST')->setData(null);
    }

    /**
     * Build Parameter as Array
     * @return array|mixed
     */
    public function buildParams(){
        return $this->keyword;
    }
}
