<?php


namespace console\controllers;


use common\components\amazon\AmazonApiV2Client;
use common\components\ebay\EbayApiV3Client;
use common\lib\AmazonSearchProductGate;
use common\lib\EbaySearchForm;
use common\models\amazon\AmazonSearchForm;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class EmulatorController extends Controller
{
    public function actionEmulatorOrder($token, $number , $portal = 'ebay', $keyWord = '', $cate_id = ''){
        if($portal == 'ebay'){
            $search = new EbaySearchForm();
            $search->keywords = $keyWord;
            $search->categories = $cate_id ? explode(',',$cate_id) : [];
            $data = EbayApiV3Client::Search($search);
            $data = json_decode($data,true);
            $k = 0;
            if($data['data']['products']){
                foreach ($data['data']['products'] as $product){
                    if($k == $number){
                        echo "Chạy xong!";
                        break;
                    }
                    $param['source'] = 'ebay';
                    $param['seller'] = $product['provider']['name'];
                    $param['quantity'] = 1;
                    $param['image'] = $product['image'];
                    $param['sku'] = $product['item_id'];
                    $param['parentSku'] = $product['item_id'];
                    $keyCart = $this->createCart($token,$param);
                    if($keyCart){
                        $orderCode = $this->checkOut($token, $keyCart);
                        if($orderCode){
                            echo $orderCode."\n";
                            $k ++;
                        }   else{
                            echo "Khoong tao duoc order\n";
                        }
                    }else{
                        echo "Khoong tao duoc order\n";
                    }
                }
            }
        }else{
            $search = new AmazonSearchForm();
            $search->keyword = $keyWord;
            $search->node_ids = $cate_id;
            $data = AmazonApiV2Client::search($search);
            print_r($data['response']['products'][0]);
            die;
        }

    }

    public function actionCreateOrderByArray($token){
        $params = ArrayHelper::getValue(\Yii::$app->params,'array_cart');
        foreach ($params as $param){
            $keyCart = $this->createCart($token,$param);
            if($keyCart){
                $orderCode = $this->checkOut($token, $keyCart);
                if($orderCode){
                    echo $orderCode."\n";
                }   else{
                    echo "Khoong tao duoc order\n";
                }
            }else{
                echo "Khoong tao duoc order\n";
            }
        }
    }
    function createCart($token,$param){
        $url = ArrayHelper::getValue(\Yii::$app->params,'url_api','http://weshop-v4.back-end.local.vn').'/v1/cart';
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest();
        $request->setFullUrl($url);
        $request->setFormat('json');
        $request->setMethod('POST');
        $request->setData($param);
        $request->setHeaders([
            'Authorization' => 'Bearer '.$token
        ]);
        $response = $client->send($request);
        if (!$response->isOk) {
            $res = $response->getData();
            echo $res['messages'].PHP_EOL;
//                return $res['messages'];
        }
        $res = $response->getData();
        return isset($res['data']['key']) ? $res['data']['key'] : false;
    }
    function checkOut($token,$key){
        $url = ArrayHelper::getValue(\Yii::$app->params,'url_api','http://weshop-v4.back-end.local.vn').'/v1/check-out';
        $param['cartIds'] = $key;
        $param['paymentProvider'] = 1;
        $param['paymentMethod'] = 1;
        $param['bankCode'] = '';
        $param['couponCode'] = '';
        $client = new \yii\httpclient\Client();
        $request = $client->createRequest();
        $request->setFullUrl($url);
        $request->setFormat('json');
        $request->setMethod('POST');
        $request->setData($param);
        $request->setHeaders([
            'Authorization' => 'Bearer '.$token
        ]);
        $response = $client->send($request);
        if (!$response->isOk) {
            $res = $response->getData();
            echo $res['messages'].PHP_EOL;
//                return $res['messages'];
        }
        $res = $response->getData();
        if($res['data']){
            foreach ($res['data'] as $k => $v){
                return $k;
            }
        }
    }

}