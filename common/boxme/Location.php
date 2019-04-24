<?php

namespace common\boxme;

use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class Location {

    const COUNTRY_VN = 'VN';
    const COUNTRY_ID = 'ID';

    const CURRENCY_VN = 'VND';
    const CURRENCY_ID= 'IDR';

    const PROVINCE_ULR = 'https://s.boxme.asia/api/v1/locations/countries/{country_code}/provinces/';
    const DISTRICT_URL = 'https://s.boxme.asia/api/v1/locations/countries/{country_code}/{province_id}/district/';

    public $systemCountry;
    public $systemProvince;
    public $systemDistrict;
    public $zipCode;

    public function mapping(){

    }

    public function createHttpRequest($url){
        $client = new Client();
        $request = $client->get($url);
        $response = $client->send($request);
        if(!$response->isOk){
            return false;
        }
        $response = $response->getData();
        if(is_array($response) && isset($response['error']) &&  $response['error'] === false){
            return ArrayHelper::getValue($response,'data',false);
        }
        return false;
    }
}