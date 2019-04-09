<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 11:20
 */

namespace common\products\ebay;


use Yii;
use Exception;
use common\products\BaseGate;

class EbayGate extends BaseGate
{

    /**
     * @param $params
     * @param bool $refresh
     * @return array|mixed
     * @throws \yii\base\InvalidConfigException
     * Search Pages Ebay of Weshop
     */
    public function search($params, $refresh = false)
    {
        $request = new EbaySearchRequest();
        $request->load($params, '');
        if (!$request->validate()) {
            return [false, $request->getFirstErrors()];
        }
        if (!($response = $this->cache->get($request->getCacheKey())) || $refresh) {
            list($success, $response) = $this->searchRequest($request->params());
            $this->cache->set($request->getCacheKey(), $response, $success === true ? self::MAX_CACHE_DURATION : 0);
        }
        return [true, (new EbaySearchResponse($this))->parser($response)];
    }

    /**
     * @param $params
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function searchRequest($params)
    {
        $httpClient = $this->getHttpClient();
        $httpRequest = $httpClient->createRequest();
        $httpRequest->setUrl($this->searchUrl);
        $httpRequest->setData($params);
        $httpRequest->setFormat('json');
        $httpRequest->setMethod('POST');
        try {
            $httpResponse = $httpClient->send($httpRequest);
            $response = $httpResponse->getData();
            if (!$httpResponse->getIsOk()) {
                return [false, $response];
            }
            return [true, $response];
        } catch (Exception $exception) {
            Yii::error($exception);
            return [false, $exception->getMessage()];
        }
    }

    /**
     * @param $condition
     * @param bool $refresh
     * @return array|mixed
     * @throws \yii\base\InvalidConfigException
     * Lấy details Ebay
     */
    public function lookup($condition, $refresh = false)
    {
        $request = new EbayDetailRequest();
        $request->keyword = $condition;
        if (!($response = $this->cache->get($request->getCacheKey())) || $refresh) {
            list($ok, $response) = $this->lookupRequest($request->params());
            $this->cache->set($request->getCacheKey(), $response, $ok === true ? self::MAX_CACHE_DURATION : 0);
        }
        return [true, (new EbayDetailResponse($this))->parser($response)];

    }

    /**
     * @param $params
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function lookupRequest($params)
    {
        $httpClient = $this->getHttpClient();
        $httpRequest = $httpClient->createRequest();
        $this->lookupUrl .= "?id={$params}";
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
            return [true, $data];
        } catch (Exception $exception) {
            Yii::error($exception);
            return [false, $exception->getMessage()];
        }
    }
}
