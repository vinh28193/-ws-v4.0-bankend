<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 11:20
 */

namespace common\products\ebay;

use common\products\BaseGate;
use Yii;

class EbayGate extends BaseGate
{

    public function search($params, $refresh = false)
    {
        $request = new EbaySearchRequest();
        $request->load($params, '');
        if (!$request->validate()) {
            return [false, $request->getFirstErrors()];
        }
        $httpClient = $this->getHttpClient();
        $httpRequest = $httpClient->createRequest();
        $httpRequest->setUrl($this->searchUrl);
        $httpRequest->setData($request->params());
        $httpRequest->setFormat('json');
        $httpRequest->setMethod('POST');
        try {
            $httpResponse = $httpClient->send($httpRequest);
            $response = $httpResponse->getData();
            if (!$httpResponse->getIsOk()) {
                return [false, $response];
            }
            return [true, (new EbaySearchResponse($this))->parser($response)];
        } catch (\Exception $exception) {
            Yii::error($exception);
            return [false, $exception->getMessage()];
        }

    }

    public function lookup($condition, $refresh = false)
    {
        $request = new EbayDetailRequest();
        $request->keyword = $condition;
        if (($product = $this->cache->get($request->getCacheKey()))) {

        }
        $httpClient = $this->getHttpClient();
        $httpRequest = $httpClient->createRequest();
        $this->lookupUrl .= "?id={$request->params()}";
        $httpRequest->setUrl($this->lookupUrl);
        $httpRequest->setData(null);
        $httpRequest->setFormat('json');
        $httpRequest->setMethod('POST');
        try {
            $httpResponse = $httpClient->send($httpRequest);
            $data = $httpResponse->getData();
            if (!$httpResponse->getIsOk()) {
                return [false, $data];
            }
            return [true, (new EbayDetailResponse($this))->parser($data)];
        } catch (\Exception $exception) {
            Yii::error($exception);
            return [false, $exception->getMessage()];
        }
    }
}