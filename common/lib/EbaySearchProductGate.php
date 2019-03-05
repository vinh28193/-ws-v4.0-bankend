<?php
/**
 * Created by PhpStorm.
 * User: Thaim
 * Date: 9/12/2017
 * Time: 10:35 AM
 */

namespace common\lib;

use common\components\ebay\EbayApiV3Client;

class EbaySearchProductGate
{
    public static function parse(EbaySearchForm $search)
    {
        $result = EbayApiV3Client::Search($search);
//        $store = SiteService::getStoreId();
        if($result == []){
            $dataLog['status'] = 500;
        }else{
            $dataLog['status'] = 200;
        }
//        $dataLog['action'] = \Yii::$app->controller->id;
//        $dataLog['request'] = $search;
//        $dataLog['sku'] = '';
//        $dataLog['store_id'] = $store;
//        $dataLog['provider'] = 'EBAY';
//        //$dataLog['respone'] = ($data);
//        $dataLog['andress'] = $_SERVER['REMOTE_ADDR'];
//        Logging::LogProduct($dataLog);
        return json_decode($result,true);
    }

    public static function feedParse(EbaySearchForm $search)
    {
        $result = EbayApiV3Client::SearchFeed($search);
        $store = SiteService::getStoreId();
        if($result == []){
            $dataLog['status'] = 500;
        }else{
            $dataLog['status'] = 200;
        }
        $dataLog['action'] = \Yii::$app->controller->id;
        $dataLog['request'] = $search;
        $dataLog['sku'] = '';
        $dataLog['store_id'] = $store;
        $dataLog['provider'] = 'EBAY';
        //$dataLog['respone'] = ($data);
        $dataLog['andress'] = $_SERVER['REMOTE_ADDR'];
        Logging::LogProduct($dataLog);
        return json_decode($result,true);
    }

    public static function mostWatch($cat){
        return $result = EbayApiV3Client::GetMostWatch($cat);
    }

}



