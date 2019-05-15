<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/05/2018
 * Time: 02:55 PM
 */

namespace common\components;


use DOMDocument;
use DOMElement;
use phpDocumentor\Reflection\Types\String_;
use SimpleXMLElement;
use Yii;
use yii\httpclient\Request;
use yii\httpclient\XmlFormatter;

class XmlSoapFormat extends XmlFormatter
{

    public function format(Request $request)
    {
        $data = $request->getData();
        if ($data !== null) {
            if (is_array($data)) {
                if (isset($data['function']) && isset($data['filter']) && !empty($data['function']) && !empty($data['filter'])) {
                    $content = "<soapenv:Envelope>" .
                        " <soapenv:Body>" .
                        "<hs:" . $data['function'] . ">";
                    foreach ($data['filter'] as $key => $value){
                        $content = $content . "<hs:".$key.">".$value."</hs:".$key.">";
                    }
                    $content = $content . "</hs:" . $data['function'] . ">"
                        . " </soapenv:Body>"
                        . "</soapenv:Envelope>";
                    $request->setContent($content);
                }
            }
            elseif (is_string($data)){
                $request->setContent($data);
            }
            else {
                return parent::format($request);
            }

        }
        return $request;
    }
    public static function parser($response){
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response->content);
        $xml = new SimpleXMLElement($response);
        $body = $xml->xpath('//getTransactionResponse')[0];
        $array = json_decode(json_encode((array)$body), TRUE);
        return $array;
    }
}