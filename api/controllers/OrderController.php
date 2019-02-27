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
        //            "id" :"ID",
        //            "store_id" :"Store ID",
        //            "type_order" :"Type Order",
        //            "portal" :"Portal",
        //            "is_quotation" :"Is Quotation",
        //            "quotation_status" :"Quotation Status",
        //            "quotation_note" :"Quotation Note",
        //            "customer_id" :"Customer ID",
        //            "receiver_email" :"Receiver Email",
        //            "receiver_name" :"Receiver Name",
        //            "receiver_phone" :"Receiver Phone",
        //            "receiver_address" :"Receiver Address",
        //            "receiver_country_id" :"Receiver Country ID",
        //            "receiver_country_name" :"Receiver Country Name",
        //            "receiver_province_id" :"Receiver Province ID",
        //            "receiver_province_name" :"Receiver Province Name",
        //            "receiver_district_id" :"Receiver District ID",
        //            "receiver_district_name" :"Receiver District Name",
        //            "receiver_post_code" :"Receiver Post Code",
        //            "receiver_address_id" :"Receiver Address ID",
        //            "note_by_customer" :"Note By Customer",
        //            "note" :"Note",
        //            "payment_type" :"Payment Type",
        //            "sale_support_id" :"Sale Support ID",
        //            "support_email" :"Support Email",
        //            "coupon_id" :"Coupon ID",
        //            "coupon_code" :"Coupon Code",
        //            "coupon_time" :"Coupon Time",
        //            "revenue_xu" :"Revenue Xu",
        //            "xu_count" :"Xu Count",
        //            "xu_amount" :"Xu Amount",
        //            "is_email_sent" :"Is Email Sent",
        //            "is_sms_sent" :"Is Sms Sent",
        //            "total_quantity" :"Total Quantity",
        //            "promotion_id" :"Promotion ID",
        //            "difference_money" :"Difference Money",
        //            "utm_source" :"Utm Source",
        //            "seller_id" :"Seller ID",
        //            "seller_name" :"Seller Name",
        //            "seller_store" :"Seller Store",
        //            "total_final_amount_local" :"Total Final Amount Local",
        //            "total_paid_amount_local" :"Total Paid Amount Local",
        //            "total_refund_amount_local" :"Total Refund Amount Local",
        //            "total_amount_local" :"Total Amount Local",
        //            "total_fee_amount_local" :"Total Fee Amount Local",
        //            "total_counpon_amount_local" :"Total Counpon Amount Local",
        //            "total_promotion_amount_local" :"Total Promotion Amount Local",
        //            "total_origin_fee_local" :"Total Origin Fee Local",
        //            "total_origin_tax_fee_local" :"Total Origin Tax Fee Local",
        //            "total_origin_shipping_fee_local" :"Total Origin Shipping Fee Local",
        //            "total_weshop_fee_local" :"Total Weshop Fee Local",
        //            "total_intl_shipping_fee_local" :"Total Intl Shipping Fee Local",
        //            "total_custom_fee_amount_local" :"Total Custom Fee Amount Local",
        //            "total_delivery_fee_local" :"Total Delivery Fee Local",
        //            "total_packing_fee_local" :"Total Packing Fee Local",
        //            "total_inspection_fee_local" :"Total Inspection Fee Local",
        //            "total_insurance_fee_local" :"Total Insurance Fee Local",
        //            "total_vat_amount_local" :"Total Vat Amount Local",
        //            "exchange_rate_fee" :"Exchange Rate Fee",
        //            "exchange_rate_purchase" :"Exchange Rate Purchase",
        //            "currency_purchase" :"Currency Purchase",
        //            "purchase_order_id" :"Purchase Order ID",
        //            "purchase_transaction_id" :"Purchase Transaction ID",
        //            "purchase_amount" :"Purchase Amount",
        //            "purchase_account_id" :"Purchase Account ID",
        //            "purchase_account_email" :"Purchase Account Email",
        //            "purchase_card" :"Purchase Card",
        //            "purchase_amount_buck" :"Purchase Amount Buck",
        //            "purchase_amount_refund" :"Purchase Amount Refund",
        //            "purchase_refund_transaction_id" :"Purchase Refund Transaction ID",
        //            "total_weight" :"Total Weight",
        //            "total_weight_temporary" :"Total Weight Temporary",
        //            "new" :"New",
        //            "purchased" :"Purchased",
        //            "seller_shipped" :"Seller Shipped",
        //            "stockin_us" :"Stockin Us",
        //            "stockout_us" :"Stockout Us",
        //            "stockin_local" :"Stockin Local",
        //            "stockout_local" :"Stockout Local",
        //            "at_customer" :"At Customer",
        //            "returned" :"Returned",
        //            "cancelled" :"Cancelled",
        //            "lost" :"Lost",
        //            "current_status" :"Current Status",
        //            "created_at" :"Created At",
        //            "updated_at" :"Updated At",
        //            "remove" :"Remove",
        //}
    }
}