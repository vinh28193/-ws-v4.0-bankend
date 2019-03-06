<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 13:41
 */

namespace common\components;

use Yii;
use yii\httpclient\Client;

class GoogleTranslation
{
    // translate the text/html in $data. Translates to the language
    // in $target. Can optionally specify the source language
    public static function translate($sourceText, $target = 'en', $source = '')
    {
        // this is the form data to be included with the request
        $values = [
            'key' => 'AIzaSyDf5Oadsge9OmuQqvYhsm25z1Al-1dMdJ4', // holder for you API key, specified when an instance is created
            'target' => $target,
            'q' => $sourceText,
        ];

        // only include the source data if it's been specified
        if (strlen($source) > 0) {
            $values['source'] = $source;
        }

        $httpClient = new Client([
            'baseUrl' => 'https://www.googleapis.com/language',  // this is the API endpoint, as specified by Google
        ]);
        $request = $httpClient->createRequest();
        $request->addHeaders(['X-HTTP-Method-Override' => 'GET']);
        $request->setUrl('translate/v2');
        $request->setFormat(Client::FORMAT_URLENCODED);
        $request->setData($values);
        // turn the form data array into raw format so it can be used with cURL
        $response = $request->send();
        $data = $response->getData();
        if(!$response->isOk){
            Yii::error($data,__METHOD__);
        }
        // decode the response data
        $data = json_decode($data, true);

        // ensure the returned data is valid
        if (!is_array($data) || !array_key_exists('data', $data)) {
            return $sourceText;
        }

        // ensure the returned data is valid
        if (!array_key_exists('translations', $data['data'])) {
            return $data;
        }

        if (!is_array($data['data']['translations'])) {
            return $data;
        }

        // loop over the translations and return the first one.
        // if you wanted to handle multiple translations in a single call
        // you would need to modify how this returns data
        foreach ($data['data']['translations'] as $translation) {
            return $translation['translatedText'];
        }

        // assume failure since success would've returned just above
        return $data;
    }

    public static function t($keyword){
        //$connection = RedisLanguage::getConnection();
        $keyword = preg_replace('/\s\s+/', ' ', trim($keyword));
        $key = strtolower(str_replace(' ', '-', $keyword));
        //$trans = $connection->executeCommand('HGET', ['en-vi-trans', $key]);
        // if (!empty($trans)) {
        //   return $trans;
        // }
        //$keyword = self::translate($keyword, 'en');
        //$connection->executeCommand('HSET', ['en-vi-trans', $key, $result]);

        return $keyword;
    }
}