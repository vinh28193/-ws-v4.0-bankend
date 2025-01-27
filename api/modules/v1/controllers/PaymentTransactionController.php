<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 7/2/2019
 * Time: 7:23 PM
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\models\Order;
use common\models\PaymentTransaction;
use common\modelsMongo\ChatMongoWs;
use frontend\modules\payment\PaymentService;
use Yii;
use yii\helpers\ArrayHelper;


class PaymentTransactionController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'update', 'create'],
                'roles' => ['master_operation', 'tester', 'master_sale', 'sale', 'master_accountant', 'accountant', 'operation']
            ],
        ];
    }

    public function actionIndex()
    {
        $model = PaymentTransaction::find()->asArray()->all();
        return $this->response(true, 'success', $model);
    }

    public function actionUpdate($code)
    {
        /** @var PaymentTransaction[] $models */
        $post = Yii::$app->request->post();
        $type = ArrayHelper::getValue($post, 'type');
        $order_code = ArrayHelper::getValue($post, 'order_code');
        $form_update = ArrayHelper::getValue($post, 'form_update');
        $amount = ArrayHelper::getValue($form_update, 'total_paid_amount_local');
        $note_update = ArrayHelper::getValue($form_update, 'note_update_payment');
        $link_image = ArrayHelper::getValue($form_update, 'link_image');
        if ($type == 'success') {
            if(!$amount){
                return $this->response(false, 'Amount invalid. (amount = '.$amount.')');
            }
            $model = PaymentService::findChildTransaction($code, $order_code);
            if (!$model) {
                return $this->response(false, 'Cannot found transaction code!');
            }
            if ($model->transaction_type != PaymentTransaction::TRANSACTION_continue_payment && $model->transaction_type == PaymentTransaction::TRANSACTION_ADDFEE && $model->transaction_type != PaymentTransaction::TRANSACTION_TYPE_REFUND) {
                return $this->response(false, 'Transaction is not addfee or continue payment or refund!');
            }
            $tran = Yii::$app->db->beginTransaction();
            try {
                $order = Order::findOne(['ordercode' => $model->order_code]);
                $model->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                $model->total_amount_success = $amount;
                if ($model->transaction_type == PaymentTransaction::TRANSACTION_continue_payment || $model->transaction_type == PaymentTransaction::TRANSACTION_ADDFEE || $model->transaction_type === PaymentTransaction::TRANSACTION_TYPE_PAYMENT) {
                    $order->total_paid_amount_local = $order->total_paid_amount_local + $amount;
                } elseif ($model->transaction_type == PaymentTransaction::TRANSACTION_TYPE_REFUND) {
                    if ($order->total_paid_amount_local < $amount) {
                        return $this->response(false, "The refund amount is greater than the amount paid.");
                    }
                    $order->total_refund_amount_local = $order->total_refund_amount_local ? $order->total_refund_amount_local + $amount : $amount;
                    $order->refunded = Yii::$app->getFormatter()->asTimestamp('now');
                    $order->total_paid_amount_local = $order->total_paid_amount_local - $amount;
                    $order->current_status = $order->total_paid_amount_local > 0 ? Order::STATUS_REFUND_PART : Order::STATUS_REFUNDED;
                    $order->save(false);
                }
                $model->link_image = $link_image;
                $model->note = $note_update;
                if (!$model->save(0)) {
                    $tran->rollBack();
                    return $this->response(false, 'Không lưu được transaction');
                }
                if (!$order->save(0)) {
                    $tran->rollBack();
                    return $this->response(false, 'Không lưu được order');
                }
                ChatMongoWs::SendMessage("Update payment success for transaction: <b>" . $model->transaction_code . "</b>." .
                    "<br>Note: ".$note_update."<br>Amount: ".$amount."<br>Image: <a target='_blank' href='".($link_image ? $link_image : '#')."'>".($link_image ? 'Link' : 'Not have link')."</a>",$model->order_code, ChatMongoWs::TYPE_GROUP_WS);
                $tran->commit();
            } catch (\Exception $exception) {
                $tran->rollBack();
                return $this->response(false, 'Có lỗi sảy ra. Rollback');
            }
        } elseif ($type == 'cancel') {
            $model = PaymentTransaction::findOne(['transaction_code' => $code]);
            if (!$model) {
                return $this->response(false, 'Cannot found transaction code!');
            }
            if ($model->transaction_type != PaymentTransaction::TRANSACTION_continue_payment && $model->transaction_type == PaymentTransaction::TRANSACTION_ADDFEE && $model->transaction_type != PaymentTransaction::TRANSACTION_TYPE_REFUND) {
                return $this->response(false, 'Transaction is not addfee or continue payment or refund!');
            }
            if ($model->transaction_status != PaymentTransaction::TRANSACTION_STATUS_QUEUED) {
                return $this->response(false, 'Transaction status is not queued !');
            }
            if ($model->transaction_type !== PaymentTransaction::TRANSACTION_TYPE_PAYMENT && $model->transaction_type != PaymentTransaction::TRANSACTION_continue_payment && $model->transaction_type != PaymentTransaction::TRANSACTION_TYPE_REFUND) {
                $order = Order::findOne(['ordercode' => $model->order_code]);
                $order->total_final_amount_local = $order->total_final_amount_local - $model->transaction_amount_local;
                $order->save(0);
            }
            $model->transaction_status = PaymentTransaction::TRANSACTION_STATUS_CANCEL;
            $model->save(0);
            ChatMongoWs::SendMessage("Update trạng thái cancel cho payment transaction: <b>" . $model->transaction_code . "</b>.",$model->order_code, ChatMongoWs::TYPE_GROUP_WS);
        } else {
            $model = PaymentTransaction::findOne(['order_code' => $code]);
            if (!$model) {
                return $this->response(false, 'Cannot found transaction code!');
            }
            $model->transaction_status = 'SUCCESS';
            if (isset($post['note'])) {
                $model->note = $post['note'];
                $model->updated_at = Yii::$app->getFormatter()->asTimestamp('now');
            }
            if (isset($post['link_image'])) {
                $model->link_image = $post['link_image'];
            }
            $model->save(false);
        }
        return $this->response(true, 'success');
    }

    public function actionView($code)
    {

        $q = PaymentTransaction::find()->select([
            'id',
            'store_id',
            'order_code',
            'transaction_code',
            'transaction_amount_local',
            'total_amount_success',
            'transaction_type',
            'transaction_status',
            'transaction_description',
            'note',
            'link_image',
            'third_party_transaction_link',
            'payment_bank_code',
            'created_at',
            'updated_at',
        ])->where(['order_code' => $code]);

//        $model = (clone $q)->andWhere(['transaction_status' => 'SUCCESS'])->one();
//        if ($model === null) {
//            $model = $q->orderBy(['id' => SORT_DESC])->limit(1)->one();
//        }
        $model = $q->orderBy(['id' => SORT_DESC])->asArray()->all();
        return $this->response(true, 'success', $model);
    }

    public function actionCreate()
    {
        $order_code = Yii::$app->request->post('order_code');
        $to = Yii::$app->request->post('to');
        $type = Yii::$app->request->post('type');
        $amount = Yii::$app->request->post('amount');
        $description = Yii::$app->request->post('description');
        $order_code = $type == 'transfer' ? Yii::$app->request->post('from') : $order_code;
        $order = Order::findOne(['ordercode' => $order_code]);
        if ($order) {
            if ($type == PaymentTransaction::PAYMENT_TYPE_ADDFEE) {
                $order->total_final_amount_local += $amount;
                $order->save(false);
                $paymentTransaction = new PaymentTransaction();
                $paymentTransaction->store_id = $order->store_id;
                $paymentTransaction->customer_id = $order->customer_id;
                $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_ADDFEE;
                $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_QUEUED;
                $paymentTransaction->transaction_customer_name = $order->receiver_name;
                $paymentTransaction->transaction_customer_email = $order->receiver_email;
                $paymentTransaction->transaction_customer_phone = $order->receiver_phone;
                $paymentTransaction->transaction_customer_address = $order->receiver_address;
                $paymentTransaction->transaction_customer_city = $order->receiver_province_name;
                $paymentTransaction->transaction_customer_postcode = $order->receiver_post_code;
                $paymentTransaction->transaction_customer_district = $order->receiver_district_name;
                $paymentTransaction->transaction_customer_country = $order->receiver_country_name;
                $paymentTransaction->order_code = $order->ordercode;
                $paymentTransaction->shipping = 0;
                $paymentTransaction->payment_type = PaymentTransaction::PAYMENT_TYPE_ADDFEE;
                $paymentTransaction->carts = '';
                $paymentTransaction->transaction_description = $description;
                $paymentTransaction->total_discount_amount = 0;
                $paymentTransaction->before_discount_amount_local = $amount;
                $paymentTransaction->transaction_amount_local = $amount;
                $paymentTransaction->created_at = time();
                $paymentTransaction->save(0);
                $paymentTransaction->transaction_code = PaymentService::generateTransactionCode('PM' . $paymentTransaction->id);
                $paymentTransaction->save(0);
                $link = 'https://' . ($order->store_id === 1 ? 'weshop.com.vn' : 'weshop.co.id') . '/checkout.html?code=' . $paymentTransaction->transaction_code;
                ChatMongoWs::SendMessage('Added an addition, code: <b>' . $paymentTransaction->transaction_code . '</b><br>note: ' . $description . '</b>Link payment this addfee:' . $link, $paymentTransaction->order_code, ChatMongoWs::TYPE_GROUP_WS);
                return $this->response(true, "add payment " . $paymentTransaction->transaction_code . " order code $order_code success");
            } elseif ($type == PaymentTransaction::PAYMENT_TYPE_continue_payment) {
                if(!$amount){
                    return $this->response(false, "Amount must > 0");
                }
                $paymentTransaction = new PaymentTransaction();
                $paymentTransaction->store_id = $order->store_id;
                $paymentTransaction->customer_id = $order->customer_id;
                $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_continue_payment;
                $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_QUEUED;
                $paymentTransaction->transaction_customer_name = $order->receiver_name;
                $paymentTransaction->transaction_customer_email = $order->receiver_email;
                $paymentTransaction->transaction_customer_phone = $order->receiver_phone;
                $paymentTransaction->transaction_customer_address = $order->receiver_address;
                $paymentTransaction->transaction_customer_city = $order->receiver_province_name;
                $paymentTransaction->transaction_customer_postcode = $order->receiver_post_code;
                $paymentTransaction->transaction_customer_district = $order->receiver_district_name;
                $paymentTransaction->transaction_customer_country = $order->receiver_country_name;
                $paymentTransaction->order_code = $order->ordercode;
                $paymentTransaction->shipping = 0;
                $paymentTransaction->payment_type = PaymentTransaction::PAYMENT_TYPE_continue_payment;
                $paymentTransaction->carts = '';
                $paymentTransaction->transaction_description = $description;
                $paymentTransaction->total_discount_amount = 0;
                $paymentTransaction->before_discount_amount_local = $amount;
                $paymentTransaction->transaction_amount_local = $amount;
                $paymentTransaction->created_at = time();
                $paymentTransaction->save(0);
                $paymentTransaction->transaction_code = PaymentService::generateTransactionCode('PM' . $paymentTransaction->id);
                $paymentTransaction->save(0);
                ChatMongoWs::SendMessage('Tạo giao dịch tiếp tục thanh toán: <b>' . $paymentTransaction->transaction_code . '</b><br>Ghi chú: ' . $description, $paymentTransaction->order_code, ChatMongoWs::TYPE_GROUP_WS);
                return $this->response(true, "add payment " . $paymentTransaction->transaction_code . " order code $order_code success");
            } elseif ($type == PaymentTransaction::PAYMENT_TYPE_REFUND) {
                if($order->total_paid_amount_local < $amount || !$amount){
                    return $this->response(false, "Amount must equal or less Remaining Amount");
                }
                $paymentTransaction = new PaymentTransaction();
                $paymentTransaction->store_id = $order->store_id;
                $paymentTransaction->customer_id = $order->customer_id;
                $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_TYPE_REFUND;
                $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_QUEUED;
                $paymentTransaction->transaction_customer_name = $order->receiver_name;
                $paymentTransaction->transaction_customer_email = $order->receiver_email;
                $paymentTransaction->transaction_customer_phone = $order->receiver_phone;
                $paymentTransaction->transaction_customer_address = $order->receiver_address;
                $paymentTransaction->transaction_customer_city = $order->receiver_province_name;
                $paymentTransaction->transaction_customer_postcode = $order->receiver_post_code;
                $paymentTransaction->transaction_customer_district = $order->receiver_district_name;
                $paymentTransaction->transaction_customer_country = $order->receiver_country_name;
                $paymentTransaction->order_code = $order->ordercode;
                $paymentTransaction->shipping = 0;
                $paymentTransaction->payment_type = PaymentTransaction::PAYMENT_TYPE_REFUND;
                $paymentTransaction->carts = '';
                $paymentTransaction->transaction_description = $description;
                $paymentTransaction->total_discount_amount = 0;
                $paymentTransaction->before_discount_amount_local = $amount;
                $paymentTransaction->transaction_amount_local = $amount;
                $paymentTransaction->created_at = time();
                $paymentTransaction->save(0);
                $paymentTransaction->transaction_code = PaymentService::generateTransactionCode('PM' . $paymentTransaction->id);
                $paymentTransaction->save(0);
                ChatMongoWs::SendMessage('Tạo giao dịch hoàn tiền: <b>' . $paymentTransaction->transaction_code . '</b><br>Ghi chú: ' . $description, $paymentTransaction->order_code, ChatMongoWs::TYPE_GROUP_WS);
                return $this->response(true, "refund transaction " . $paymentTransaction->transaction_code . " order code $order_code success");
            } elseif ($type == 'transfer') {
                if ($order->total_paid_amount_local < 0) {
                    return $this->response(false, "The amount paid = 0.");
                }
                $orderTo = Order::findOne(['ordercode' => $to]);
                if (!$orderTo) {
                    return $this->response(false, "Cannot find order target: " . $to);
                }
                $amount = $order->total_paid_amount_local;
                $orderTo->total_paid_amount_local += $amount;
                $orderTo->save(0);
                $order->total_paid_amount_local = 0;
                $order->transfer_to = $orderTo->ordercode;
                $order->refund_transfer = time();
                $order->current_status = Order::STATUS_REFUND_TRANSFER;
                $order->save(false);
                $paymentTransaction = new PaymentTransaction();
                $paymentTransaction->store_id = $order->store_id;
                $paymentTransaction->customer_id = $order->customer_id;
                $paymentTransaction->transaction_type = PaymentTransaction::TRANSACTION_TYPE_REFUND_TRANSFER;
                $paymentTransaction->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                $paymentTransaction->transaction_customer_name = $order->receiver_name;
                $paymentTransaction->total_amount_success = $amount;
                $paymentTransaction->transaction_customer_email = $order->receiver_email;
                $paymentTransaction->transaction_customer_phone = $order->receiver_phone;
                $paymentTransaction->transaction_customer_address = $order->receiver_address;
                $paymentTransaction->transaction_customer_city = $order->receiver_province_name;
                $paymentTransaction->transaction_customer_postcode = $order->receiver_post_code;
                $paymentTransaction->transaction_customer_district = $order->receiver_district_name;
                $paymentTransaction->transaction_customer_country = $order->receiver_country_name;
                $paymentTransaction->order_code = $order->ordercode;
                $paymentTransaction->shipping = 0;
                $paymentTransaction->payment_type = PaymentTransaction::TRANSACTION_TYPE_REFUND_TRANSFER;
                $paymentTransaction->carts = '';
                $paymentTransaction->transaction_description = "Chuyển tiền thanh toán cho order " . $to;
                $paymentTransaction->total_discount_amount = 0;
                $paymentTransaction->before_discount_amount_local = $amount;
                $paymentTransaction->transaction_amount_local = $amount;
                $paymentTransaction->created_at = time();
                $paymentTransaction->save(0);
                $paymentTransaction->transaction_code = PaymentService::generateTransactionCode('PM' . $paymentTransaction->id);
                $paymentTransaction->save(0);
                ChatMongoWs::SendMessage("Chuyển tiền thanh toán cho order <b>" . $to . '</b>. Với mã giao dịch <b>' . $paymentTransaction->transaction_code . '</b>', $paymentTransaction->order_code, ChatMongoWs::TYPE_GROUP_WS);
                $paymentTransaction_2 = new PaymentTransaction();
                $paymentTransaction_2->store_id = $orderTo->store_id;
                $paymentTransaction_2->customer_id = $orderTo->customer_id;
                $paymentTransaction_2->transaction_type = PaymentTransaction::TRANSACTION_TYPE_PAYMENT_TRANSFER;
                $paymentTransaction_2->transaction_status = PaymentTransaction::TRANSACTION_STATUS_SUCCESS;
                $paymentTransaction_2->total_amount_success = $amount;
                $paymentTransaction_2->transaction_customer_name = $orderTo->receiver_name;
                $paymentTransaction_2->transaction_customer_email = $orderTo->receiver_email;
                $paymentTransaction_2->transaction_customer_phone = $orderTo->receiver_phone;
                $paymentTransaction_2->transaction_customer_address = $orderTo->receiver_address;
                $paymentTransaction_2->transaction_customer_city = $orderTo->receiver_province_name;
                $paymentTransaction_2->transaction_customer_postcode = $orderTo->receiver_post_code;
                $paymentTransaction_2->transaction_customer_district = $orderTo->receiver_district_name;
                $paymentTransaction_2->transaction_customer_country = $orderTo->receiver_country_name;
                $paymentTransaction_2->order_code = $orderTo->ordercode;
                $paymentTransaction_2->shipping = 0;
                $paymentTransaction_2->payment_type = PaymentTransaction::TRANSACTION_TYPE_PAYMENT_TRANSFER;
                $paymentTransaction_2->carts = '';
                $paymentTransaction_2->transaction_description = "Nhận tiền thanh toán từ order " . $order_code;
                $paymentTransaction_2->total_discount_amount = 0;
                $paymentTransaction_2->before_discount_amount_local = $amount;
                $paymentTransaction_2->transaction_amount_local = $amount;
                $paymentTransaction_2->created_at = time();
                $paymentTransaction_2->save(0);
                $paymentTransaction_2->transaction_code = PaymentService::generateTransactionCode('PM' . $paymentTransaction_2->id);
                $paymentTransaction_2->save(0);
                ChatMongoWs::SendMessage("Nhận tiền thanh toán từ order <b>" . $order_code . '</b>. Với mã giao dịch nhận <b>' . $paymentTransaction_2->transaction_code . '</b> và mã giao dịch chuyển là: <b>' . $paymentTransaction->transaction_code . '</b>', $paymentTransaction_2->order_code, ChatMongoWs::TYPE_GROUP_WS);
                return $this->response(true, "transaction transfer : " . $paymentTransaction->transaction_code . " - " . $paymentTransaction_2->transaction_code . "  success");
            }
        } else {
            return $this->response(false, 'Order code not found!');
        }
    }
}
