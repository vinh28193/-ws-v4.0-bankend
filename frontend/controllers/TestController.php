<?php


namespace frontend\controllers;

use common\boxme\InternationalShippingCalculator;
use common\components\cart\CartHelper;
use common\components\cart\CartManager;
use common\components\employee\Employee;
use common\helpers\ObjectHelper;
use common\helpers\WeshopHelper;
use common\models\Store;
use common\models\User;
use common\promotion\PromotionForm;
use Courier\CalculateFeeRequest;
use Courier\CourierClient;
use frontend\modules\payment\models\Order;
use frontend\modules\payment\providers\mcpay\McPayProvider;
use frontend\modules\payment\providers\nganluong\ver3_2\NganLuongClient;
use frontend\modules\payment\providers\nganluong\ver3_2\NganluongHelper;
use frontend\modules\payment\providers\nicepay\NicePayClient;
use Yii;
use common\components\cart\storage\MongodbCartStorage;
use frontend\modules\payment\PaymentService;
use frontend\modules\payment\providers\alepay\AlepayClient;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class TestController extends FrontendController
{

    public function actionTestMe()
    {
        echo PaymentService::createReturnUrl(42);
        die;
    }

    public function actionTestRsa()
    {

    }

    public function actionAlepay()
    {
        $alepay = new AlepayClient();

        $res = $alepay->getInstallmentInfo(10000000.00, 'VND')['data'];
        $res = json_decode($res, true);
        $results = [];
        foreach ($res as $bank) {

            $periods = [];
            foreach ($bank['paymentMethods'] as $method) {
                if ($method['paymentMethod'] !== 'VISA') {
                    continue;
                }
                $periods = $method['periods'];
            }
            $results[] = [
                'code' => $bank['bankCode'],
                'name' => $bank['bankName'],
                'method' => 'VISA',
                'periods' => $periods
            ];
        }
        echo "<pre>";
        var_dump($results);
        echo "</pre>";
        die;
    }

    public function actionTestCart()
    {
        /** @var  $cartManager CartManager */

        $cartManager = Yii::$app->cart;

        $cartManager->addItem('shopping', [
            'source' => 'ebay',
            'sku' => '100-99800902-NRC',
            'id' => '163586118957',
            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
            'quantity' => 2,
            'image' => 'https://i.ebayimg.com/00/s/MTQwMFgxNDAw/z/fCkAAOSwuN9cgrz3/$_1.JPG'
        ]);
//
//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => '100-99000000-02',
//            'id' => '163189059666',
//            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
//            'quantity' => 1,
//            'image' => 'https://d3d71ba2asa5oz.cloudfront.net/12022392/images/elite_65t_earbuds_rgb_72dpi.jpg'
//        ]);
//
//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => '204151',
//            'id' => '163655512954',
//            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
//            'quantity' => 1,
//            'image' => 'https://i.ebayimg.com/00/s/MTQwMFgxNDAw/z/Qr0AAOSwuCVcuKWl/$_1.JPG'
//        ]);
//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => '100-99600900-02',
//            'id' => '163314595720',
//            'sellerId' => 'amFicmEtY29tcGFueS1zdG9yZS0t',
//            'quantity' => 1,
//            'image' => 'https://i.ebayimg.com/00/s/MTQwMFgxNDAw/z/kGwAAOSwnFpbwy9H/$_1.JPG'
//        ]);
//        $cartManager->addItem('shopping', [
//            'source' => 'ebay',
//            'sku' => 'YC1060-I;0',
//            'id' => '232738139862',
//            'sellerId' => 'dGVtcG9yZXgtaW50ZXJuYXRpb25hbC0t',
//            'quantity' => 1,
//            'image' => 'https://i.ebayimg.com/00/s/MTYwMFgxNjAw/z/3hAAAOSwQ~ha1u4Q/$_1.JPG'
//        ]);
//        var_dump($cartManager->filterItem('shopping', ['source' => 'ebay', 'seller' => 'Y2hvb3Nlc21hcnQtLQ==']));

//        $item1 = $cartManager->updateItem('shopping', '5ced1365e419ac1fb80057b9', ['id' => '163586118957', 'sku' => '100-99800902-NRC'], ['quantity' => 1]);
//        $cartManager->removeItem('shopping', '5cee2e6ce419ac05a00007eb', ['id' => '163655512954', 'sku' => '204151']);
        $item2 = $cartManager->getItem('shopping', '5cef4645e419ac46000075a0');

        var_dump($item2);
        die;
    }

    public function actionTime()
    {
        $formater = Yii::$app->formatter;
        $dateTime = new \DateTime('now');
        $dateTime->setTime(23, 59, 59, 59);
        var_dump($formater->asDatetime($dateTime));
        die;
    }

    public function actionTestCount()
    {
        $storage = new MongodbCartStorage();
        $authManager = Yii::$app->authManager;
        $saleIds = $authManager->getUserIdsByRole('sale');
        $masterSaleIds = $authManager->getUserIdsByRole('master_sale');
        $supporters = User::find()->indexBy('id')->select(['id', 'email', 'username'])->where(['or', ['id' => $saleIds], ['id' => $masterSaleIds]])->all();

        $ids = array_keys($supporters);
        $calculateToday = ArrayHelper::map($storage->calculateSupported($ids), '_id', function ($elem) {
            return ['count' => $elem['count'], 'price' => $elem['price']];
        });

        $countData = [];
        foreach ($ids as $id) {
            $c = 0;
            if (isset($calculateToday[$id]) && ($forSupport = $calculateToday[$id]) !== null && !empty($forSupport) && isset($forSupport['count'])) {
                $c = $forSupport['count'];
            }
            $countData[$id] = $c;
        }
        asort($countData);

        $sQMin = WeshopHelper::sortMinValueArray($countData);

        $priceResult = [];

        foreach ($sQMin as $id => $val) {
            $p = 0;
            if (isset($calculateToday[$id]) && ($forSupport = $calculateToday[$id]) !== null && !empty($forSupport) && isset($forSupport['price'])) {
                $p = $forSupport['price'];
            }
            $priceResult[$id] = $p;
        }
        $priceResult = array_keys($priceResult);
        $id = array_shift($priceResult);
        if (($assigner = ArrayHelper::getValue($supporters, $id)) === null) {
            $assigner = array_shift($supporters);
        }
        return ['id' => $assigner->id, 'email' => $assigner->email, 'username' => $assigner->username];
        var_dump($assigner);
        die;
    }

    public function actionI18n()
    {
        echo Yii::t('javascript', 'Hello {name}', ['name' => 'VINH']);
        die;
    }

    public function actionSql()
    {
        $user = User::find()->where(['id' => 1])->one();
        var_dump($user->getPrimaryKey());
        die;
    }

    public function actionCreate()
    {
        $provice = new McPayProvider();
        $provice->amount = '20000';
        $provice->orderId = 'asdasdasd';
        $provice->billName = 'asd asDSA';
        var_dump($provice->createCheckOutUrl());
        die;
    }

    public function actionPromotion()
    {
        $posts = require dirname(dirname(__DIR__)) . '/common/promotion/mock-post.php';
        $promotionForm = new PromotionForm();
        $promotionForm->load($posts, '');

        var_dump($promotionForm->checkPromotion());
        die;
    }

    public function actionNganLuong()
    {
        $client = new NganLuongClient();
        var_dump($client->GetRequestField('QRCODE_AGB'));
        die;
    }

    public function actionCheckPaymentStatus($token)
    {

        $client = new NganLuongClient();
        var_dump($client->GetTransactionDetail($token));
        die;
    }

    public function actionCustomFee()
    {
        $message = [];
        $data = require dirname(dirname(__DIR__)) . '\common\models\category_group.php';
        foreach ($data as $array) {
            $rules = [];
            if ($array['condition_data'] !== null) {
                foreach ($array['condition_data'] as $condition) {
                    $calc = new \common\calculators\Calculator();
                    $calc->register($condition);
                    $rules[] = $calc->deception();
                }
            }
            $str = "Group {$array['id']}: `{$array['name']}`";
            if (!empty($rules)) {
                $str .= ' calculator: ' . implode(', ', $rules);
            }
            $message[] = $str;
        }
        var_dump($message);
        die;
    }

    public function actionTestSale()
    {
        $sale = $this->actionTestCount();
        var_dump($sale);
        die();
    }

    public function actionGetCourier()
    {
        $calculator = new InternationalShippingCalculator();

        $shipment = <<<JSON
{
  "ship_from": {
    "country": "US",
    "pickup_id": 35549
  },
  "ship_to": {
    "contact_name": "1212121",
    "company_name": "",
    "email": "",
    "address": "18 Đường Tam Trinh, Mai Động, Hai Bà Trưng, Hà Nội, Việt Nam",
    "address2": "",
    "phone": "12121212121",
    "phone2": "",
    "country": "VN",
    "province": 1,
    "district": 7,
    "zipcode": "",
    "tax_id": ""
  },
  "shipments": {
    "content": "",
    "total_parcel": 1,
    "total_amount": 4000000,
    "chargeable_weight": 500,
    "description": "",
    "amz_shipment_id": "",
    "parcels": [
      {
        "dimension": {
          "width": 0,
          "height": 0,
          "length": 0
        },
        "weight": 500,
        "amount": 4000000,
        "description": "",
        "dg_code": "",
        "hs_code": "",
        "items": [
          {
            "sku": "593103644595420",
            "label_code": "",
            "origin_country": "",
            "name": "Nhà máy trực tiếp camera không dây mạng wifi điện thoại di động từ xa HD nhìn đêm nhà màn hình trong nhà đặt - 720P100W pixel (không có thẻ nhớ)",
            "desciption": "",
            "weight": 500,
            "amount": 400000,
            "customs_value": 400000,
            "quantity": 7
          }
        ]
      }
    ]
  },
  "config": {
    "preview": "Y",
    "return_mode": 0,
    "insurance": "N",
    "document": 0,
    "currency": "VND",
    "unit_metric": "metric",
    "sort_mode": "best_rating",
    "auto_approve": "Y",
    "create_by": 0,
    "create_from": "create_order_netsale",
    "order_type": "dropship",
    "check_stock": "N"
  },
  "payment": {
    "cod_amount": 0,
    "fee_paid_by": "sender"
  },
  "referral": {
    "order_number": "",
    "coupon_code": ""
  }
}
JSON;
        $shipment = json_decode($shipment, true);
        $couriers = $calculator->CalculateFee($shipment, 23, 'VN');
        var_dump($couriers);
        die;
    }

    public function actionSaleAssign()
    {
        $employee = new Employee();
        @unlink($employee->getActiveRule()->saveFileName);
        for ($index = 1; $index <= 500; $index++) {
            var_dump($employee->getAssign());
        }
        die;
    }

    public function actionSupportTime()
    {
        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'));
        var_dump([
            'today' => (clone $dateTime)->modify('today')->format('Y-m-d H:s:i'),
            'Last Monday' => (clone $dateTime)->modify('Last Monday - 7 days')->format('Y-m-d H:s:i'),
            'Last Sunday' => (clone $dateTime)->modify('Last Sunday')->format('Y-m-d H:s:i'),
        ]);
        die;
    }
}