<?php

namespace api\controllers;


use yii\filters\AccessControl;
use common\models\db\Order;
use api\behaviours\Verbcheck;
use api\behaviours\Apiauth;

use Yii;



class OrderController extends RestController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        return $behaviors + [

           'apiauth' => [
               'class' => Apiauth::className(),
               'exclude' => [],
               'callback'=>[]
           ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'index'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['*'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => Verbcheck::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'create' => ['POST'],
                    'update' => ['PUT'],
                    'view' => ['GET'],
                    'delete' => ['DELETE']
                ],
            ],

        ];
    }

    public function actionIndex()
    {
        $params = $this->post['search'];
        $response = Order::search($params);
        //Yii::$app->api->sendSuccessResponse($response['data'], $response['info']);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->response->data  =   array_merge($response['data'], $response['info']);

    }

    public function actionCreate()
    {
        if (isset($this->post) !== null)  {
            $model = new Order;
            $model->attributes = $this->post;

            if ($model->save()) {
                //Yii::$app->api->sendSuccessResponse($model->attributes);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data  =   $model->attributes;
            } else {
                //Yii::$app->api->sendFailedResponse($model->errors);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data  =   $model->errors;
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }

    }

    public function actionUpdate($id)
    {
        if ($id !== null)  {
            $model = $this->findModel($id);
            $model->attributes = $this->post;

            if ($model->save()) {
                //Yii::$app->api->sendSuccessResponse($model->attributes);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data  =   $model->attributes;
            } else {
                //Yii::$app->api->sendFailedResponse($model->errors);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data  =   $model->errors;
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }

    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        $query = Order::find();
        $query->where('[[id]] = :id',[':id' => $id]);
        $query->with([
            'products',
            'orderFees',
            'packageItems',
            'walletTransactions',
            'seller',
            'saleSupport' => function(\yii\db\ActiveQuery $q){
                $q->select(['username','email','id','status', 'created_at', 'created_at']);
            }
        ]);
        if (($model = $query->one()) !== null and  $id !== null)  {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    public function actionsTest($data)
    {
//        {
//            "store_id" : 1,
//            "fees" : [
//                "origin_fee" : 36,
//                "origin_tax_fee" : 80.3,
//                "origin_shipping_fee" : 4,
//            ],
//            "type_order" : "SHOP",
//            "portal" : "AMAZON",
//            "is_quotation" : 0,
//            "quotation_status" : "",
//            "quotation_note" : "",
//            "customer_id" : 249,
//            "receiver_email" : "dieu.nghiem@hotmail.com",
//            "receiver_name" : "Bạc Vĩ",
//            "receiver_phone" : "022 511 1846",
//            "receiver_address" : "3, Thôn Diệp Đoàn, Ấp Thạch Đình, Quận Khoát Anh Bắc Giang",
//            "receiver_country_id" : 1,
//            "receiver_country_name" : "Việt Nam",
//            "receiver_province_id" : 3,
//            "receiver_province_name" : "Hà Nội",
//            "receiver_district_id" : 817,
//            "receiver_district_name" : "Phố Bì",
//            "receiver_post_code" : "750214",
//            "receiver_address_id" : 178,
//            "note_by_customer" : "Come on!\" So they sat down with wonder at the.",
//            "note" : "As they walked off together, Alice heard the.",
//            "payment_type" : "WALLET",
//            "sale_support_id" : 4,
//            "support_email" : "dcn@yahoo.com",
//            "coupon_id" : null,
//            "coupon_code" : null,
//            "coupon_time" : null,
//            "revenue_xu" : 0,
//            "xu_count" : 0,
//            "xu_amount" : 0,
//            "is_email_sent" : 0,
//            "is_sms_sent" : 0,
//            "total_quantity" : 3,
//            "promotion_id" : null,
//            "difference_money" : 0,
//            "utm_source" : null,
//            "seller_id" : 699,
//            "seller_name" : "Em. Giao Luận",
//            "seller_store" : "https://www.le.int.vn/sed-expedita-rerum-beatae-consectetur-commodi",
//            "total_final_amount_local" : 0,
//            "total_paid_amount_local" : 0,
//            "total_refund_amount_local" : 0,
//            "total_amount_local" : 10716000,
//            "total_fee_amount_local" : 0,
//            "total_counpon_amount_local" : 0,
//            "total_promotion_amount_local" : 0,
//            "exchange_rate_fee" : 23500,
//            "exchange_rate_purchase" : 0,
//            "currency_purchase" : 0,
//            "purchase_order_id" : null,
//            "purchase_transaction_id" : null,
//            "purchase_amount" : null,
//            "purchase_account_id" : null,
//            "purchase_account_email" : null,
//            "purchase_card" : null,
//            "purchase_amount_buck" : null,
//            "purchase_amount_refund" : null,
//            "purchase_refund_transaction_id" : null,
//            "total_weight" : null,
//            "total_weight_temporary" : null,
//            "new" : 1213456503,
//            "purchased" : null,
//            "seller_shipped" : null,
//            "stockin_us" : null,
//            "stockout_us" : null,
//            "stockin_local" : null,
//            "stockout_local" : null,
//            "at_customer" : null,
//            "returned" : null,
//            "cancelled" : null,
//            "lost" : null,
//            "current_status" : "NEW",
//            "created_time" : 411598298,
//            "updated_time" : 679918109,
//            "remove" : 0,
//        }
    }
}