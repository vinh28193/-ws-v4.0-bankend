<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 17:29
 * Chặn Authen theo role được gán chưa viết  test @Phuchc
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\components\StoreManager;
use common\helpers\ChatHelper;
use common\helpers\ExcelHelper;
use common\helpers\WeshopHelper;
use common\models\db\TargetAdditionalFee;
use common\models\Order;
use common\models\Product;
use common\models\StoreAdditionalFee;
use common\modelsMongo\ActiveRecordUpdateLog;
use common\modelsMongo\PaymentLogWS;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\caching\DbDependency;

/***Cache PageCache **/
class OrderController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        /*
        $behaviors['pageCache'] = [
            'class' => 'yii\filters\PageCache',
            'only' => ['index'],
            'duration' => 24 * 3600 * 365, // 1 year
            'dependency' => [
                'class' => 'yii\caching\ChainedDependency',
                'dependencies' => [
                    new DbDependency(['sql' => 'SELECT MAX(id) FROM `'.Order::tableName().'`'])
                ]
            ],
        ];
        */
        return $behaviors;
    }


    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'export'],
                'roles' => $this->getAllRoles(true),
            ],
            [
                'allow' => true,
                'actions' => ['create', 'update'],
                'roles' => $this->getAllRoles(true, 'marketing'),
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => $this->getAllRoles(true, 'user'),
                'permissions' => ['canCreate']
            ],
            [
                'allow' => true,
                'actions' => ['update', 'delete'],
                'roles' => $this->getAllRoles(true, 'user'),
            ],
            [
                'allow' => true,
                'actions' => ['confirm'],
                'roles' => $this->getAllRoles(true, 'user'),
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
            'export' => ['GET', 'POST']
        ];
    }

    /**
     *  `get given`
     *      ?q=pen&q_ref=all&tm_ref=create_time&tm=2019-01-01 00:00:00&ps=pain&page=1&per-pace=20
     * `post given`
     *  {
     *      "filter":{
     * "keyword": {"key":"all","value":"pen"},
     *          "datetime": {"key":"create_at","value":["2019-01-01 00:00:00","2019-01-30 23:59:59"]},
     *          "paymentStatus":"pain",
     *          "type":"shop",
     *          ....
     *      },
     *      "page":"1",
     *      "per-page":"20"
     * }
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->get();
        $response = Order::search($params);
        return $this->response(true, 'Success', $response);

    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionCreate()
    {
        if (isset($this->post) !== null) {
            // $this->can('canCreate',[]); // Supper is canCreate
            $model = new Order;
            $model->attributes = $this->post;
            if ($model->save()) {
                Yii::$app->api->sendSuccessResponse($model->attributes);
            } elseif ($model->save() === false) {
                Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($code)
    {
        if ($code !== null) {
            $query = Order::find()->where(['ordercode' => $code])
                ->withFullRelations()
                ->asArray()->all();
            return $this->response(true, "Get order $code success", $query);
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    /**
     * Todo
     *  common action update
     *      post given
     *          $_POST
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $StatusOrder = array(
            'new',
            'contacting',
            'awaiting_payment',
            'ready_purchase',
            'purchasing',
            'awaiting_confirm_purchase',
            'purchased',
            'delivering',
            'delivered',
            'refunded',
            'cancelled',
            'junk',
        );
        $user = Yii::$app->user->identity;
        $post = Yii::$app->request->post();
        $now = Yii::$app->getFormatter()->asTimestamp('now');
        $model = Order::findOne($id);
        //$this->can('canUpdate', $model);
        $check = $model->loadWithScenario($this->post);
        if ($model->getScenario() == 'confirmPurchase') {

            if ((Yii::$app->request->post('product_id')) !== null)
                $total = round(($model->total_paid_amount_local / $model->total_final_amount_local) * 100);
            $remaining = $model->total_final_amount_local - $model->total_paid_amount_local;
            $product_id = Yii::$app->request->post('product_id');
            $tran = Yii::$app->db->beginTransaction();
            $model->current_status = Order::STATUS_AWAITING_PAYMENT;

            if ($model->total_paid_amount_local >= 0 && $total > 30) {
                $model->current_status = Order::STATUS_READY2PURCHASE;
            }
            if (in_array($model->current_status, [Order::STATUS_READY2PURCHASE, Order::STATUS_CONTACTING, Order::STATUS_AWAITING_PAYMENT])) {
                if ($model->current_status == Order::STATUS_READY2PURCHASE) {
                    if ($model->contacting == null) {
                        $model->contacting = Yii::$app->getFormatter()->asTimestamp('now');
                    }
                    if ($model->awaiting_payment == null) {
                        $model->awaiting_payment = Yii::$app->getFormatter()->asTimestamp('now');
                    }
                    $model->ready_purchase = time();
                }
            }
            $tran->commit();
        }
        if ($model->getScenario() == 'updateOrderStatus') {
            $i = 0;
            for ($i; $i < count($StatusOrder); $i++) {
                if ($StatusOrder[$i] != $post['Order']['status']) {
                    if ($model->{$StatusOrder[$i]} == null) {
                        $model->{$StatusOrder[$i]} = $now;
                    }
                } else {
                    $model->{$StatusOrder[$i]} = $now;
                    break;
                }
            }
            if ($i < 11) {
                for ($j = $i + 1; $j < count($StatusOrder) - 1; $j++) {
                    $model->{$StatusOrder[$j]} = null;
                }
            }

            if ($post['Order']['status'] == 'ready_purchase') {
                $model->current_status = Order::STATUS_READY2PURCHASE;
            } else {
                $model->current_status = strtoupper($post['Order']['status']);
            }
        }
        if ($model->getScenario() == 'updateReady2Purchase') {
            $model->ready_purchase = $now;
            $model->current_status = Order::STATUS_READY2PURCHASE;
        }
        if ($model->getScenario() == 'cancelOrder') {
            $model->cancelled = $now;
            $model->current_status = Order::STATUS_CANCEL;
        }
        if ($model->getScenario() == 'updateTimeNull') {
            for ($k = 0; $k < count($StatusOrder); $k++) {
                if ($StatusOrder[$k] == $post['Order']['column']) {
                    break;
                }
            }
            for ($h = $k; $h < count($StatusOrder); $h++) {
                $model->{$StatusOrder[$h]} = null;
            }
            if ($StatusOrder[$k - 1] == 'ready_purchase') {
                $model->current_status = Order::STATUS_READY2PURCHASE;
            } else {
                $model->current_status = strtoupper($StatusOrder[$k - 1]);
            }
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        $action = Inflector::camel2words($model->getScenario());
        Yii::info([$dirtyAttributes, $model->getOldAttributes()], $model->getScenario());
        if ($model->getScenario() == 'editAdjustPayment') {
            /** @var  $logOrigin ActiveRecordUpdateLog */
            $logOrigin = ActiveRecordUpdateLog::find()->where(['and', ['type' => 'original'], ['object_class' => 'Order'], ['object_identity' => $model->ordercode]])->one();
            if ($logOrigin !== null) {
                $logOrigin->updateAttributes([
                    'status' => 'inactive'
                ]);
            }
            $messages = "<span class='text-danger fa-2x'>Order {$model->ordercode}</span> <br> - $action <br>{$this->resolveChatMessage($dirtyAttributes,$model)}. <br>- Update Payment Transaction: `Transaction Status` changed from `CREATE` to `SUCCESS`";
        }
        if ($model->getScenario() == 'confirmPurchase') {
            $messages = "<span class='text-danger font-weight-bold'>Order {$model->ordercode}</span><br>- Customer paid: {$total}% <br>- Remaining amount: {$remaining} <br> - $action <br> {$this->resolveChatMessage($dirtyAttributes,$model)}";
        } else {
            $messages = "<span class='text-danger font-weight-bold'>Order {$model->ordercode}</span> <br> - $action <br> {$this->resolveChatMessage($dirtyAttributes,$model)}";
        }
        $model->validate();
        if (!$model->save(false)) {
            Yii::$app->wsLog->push('order', $model->getScenario(), null, [
                'id' => $model->ordercode,
                'request' => $this->post,
                'response' => $model->getErrors()
            ]);
            return $this->response(false, $model->getFirstErrors());
        }
        if (isset($post['Order']['link_image_log'])) {
            ChatHelper::push($messages, $model->ordercode, 'GROUP_WS', 'SYSTEM', $post['Order']['link_image_log'] ? $post['Order']['link_image_log'] : null);
        } else {
            ChatHelper::push($messages, $model->ordercode, 'GROUP_WS', 'SYSTEM', null);
        }
        Yii::info("Order Log " . $model->getScenario());
        Yii::info([
            'id' => $model->ordercode,
            'request' => $this->post,
            'response' => $dirtyAttributes,
            'Order_log_type' => $model->getScenario()

        ], __CLASS__);
        Yii::$app->wsLog->push('order', $model->getScenario(), null, [
            'id' => $model->ordercode,
            'request' => $this->post,
            'response' => $messages
        ]);
        return $this->response(true, $messages);
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id, false);
        $this->can('canDelete', ['id' => $model->id]);
        $model->delete();
        return $this->response(true, "Delete order $id success", $model);
    }


    public function actionConfirm($code)
    {
        $insurance = Yii::$app->request->post('insurance', 0);
        $useInsurance = Yii::$app->request->post('useInsurance', 'Y');
        $inspection = Yii::$app->request->post('inspection', 0);
        $useInspection = Yii::$app->request->post('useInspection', 'Y');
        $packingWood = Yii::$app->request->post('packingWood', 0);
        $packingWood = floatval($packingWood);

        /** @var $order Order */
        if (($order = $this->findModel(['ordercode' => $code], false)) === null) {
            return $this->response(false, "Not found order code $code");
        }

        $allOrderFees = $order->targetFee;

        $allOrderFees = ArrayHelper::index($allOrderFees, 'name');

        $timeTimestamp = Yii::$app->formatter->asTimestamp('now');
        /** @var $storeManager StoreManager */
        $storeManager = Yii::$app->storeManager;
        $storeManager->setStore($order->store_id);
        $token = ["Confirm order {$order->ordercode}"];
        if ($insurance !== 0 && $useInsurance === 'Y') {
            if (!isset($allOrderFees['insurance_fee'])) {
                $target = new TargetAdditionalFee();
                $target->name = 'insurance_fee';
                $target->type = 'local';
                $target->discount_amount = 0;
                $target->currency = $storeManager->getCurrencyName();
                $target->label = $order->store_id === 1 ? 'Phí bảo hiểm' : 'Insurance Fee';
                $target->created_at = $timeTimestamp;
                $target->remove = 0;
                $target->target = 'order';
                $target->target_id = $order->id;
                $target->save(false);
                $allOrderFees['insurance_fee'] = $target;
                $token[] = "set {$target->label}:{$storeManager->showMoney($target->local_amount)}";
            } else if (($target = $allOrderFees['insurance_fee']) instanceof TargetAdditionalFee && !WeshopHelper::compareValue($target->amount, $insurance, 'float')) {
                $target->amount = $insurance;
                $target->local_amount = $insurance;
                $target->save(false);
                $token[] = "update {$target->label}:{$storeManager->showMoney($target->local_amount)}";
                $allOrderFees['insurance_fee'] = $target;
            }
        } else {
            if (!isset($allOrderFees['insurance_fee'])) {
                $order->total_insurance_fee_local = null;
                $token[] = "remove order `total insurance fee`";
            } elseif (($target = $allOrderFees['insurance_fee']) instanceof TargetAdditionalFee) {
                $target->amount = 0;
                $target->local_amount = 0;
                $target->save(false);
                $allOrderFees['insurance_fee'] = $target;
            }
        }

        if ($inspection !== 0 && $useInspection === 'Y') {
            $localAmount = $storeManager->roundMoney($inspection * $storeManager->getExchangeRate());
            if (!isset($allOrderFees['inspection_fee'])) {
                $target = new TargetAdditionalFee();
                $target->name = 'inspection_fee';
                $target->type = 'addition';
                $target->amount = $inspection;
                $target->local_amount = $localAmount;
                $target->discount_amount = 0;
                $target->currency = $storeManager->getCurrencyName();
                $target->label = $order->store_id === 1 ? 'Phí kiểm hàng' : 'Inspection Fee';
                $target->created_at = $timeTimestamp;
                $target->remove = 0;
                $target->target = 'order';
                $target->target_id = $order->id;
                $target->save(false);
                $allOrderFees['inspection_fee'] = $target;
                $token[] = "set {$target->label}:{$storeManager->showMoney($target->local_amount)}";
            } else if (($target = $allOrderFees['inspection_fee']) instanceof TargetAdditionalFee && !WeshopHelper::compareValue($target->amount, $inspection, 'float')) {
                $target->amount = $inspection;
                $target->local_amount = $localAmount;
                $target->save(false);
                $token[] = "update {$target->label}:{$storeManager->showMoney($target->local_amount)}";
                $allOrderFees['inspection_fee'] = $target;
            }

        } else {
            if (!isset($allOrderFees['inspection_fee'])) {
                $order->total_inspection_fee_local = null;
                $token[] = "remove order `total inspection fee`";
            } elseif (($target = $allOrderFees['inspection_fee']) instanceof TargetAdditionalFee) {
                $target->amount = 0;
                $target->local_amount = 0;
                $target->save(false);
                $token[] = "remove {$target->label}:{$storeManager->showMoney($target->local_amount)}";
                $allOrderFees['inspection_fee'] = $target;
            }
        }

        if ($packingWood !== 0) {
            $localAmount = $storeManager->roundMoney($packingWood * $storeManager->getExchangeRate());
            if (!isset($allOrderFees['packing_fee'])) {
                $target = new TargetAdditionalFee();
                $target->name = 'packing_fee';
                $target->type = 'addition';
                $target->amount = $packingWood;
                $target->local_amount = $storeManager->roundMoney($packingWood * $storeManager->getExchangeRate());
                $target->discount_amount = 0;
                $target->currency = $storeManager->getCurrencyName();
                $target->label = $order->store_id === 1 ? 'Phí đóng hàng' : 'Packing Fee';
                $target->created_at = $timeTimestamp;
                $target->remove = 0;
                $target->target = 'order';
                $target->target_id = $order->id;
                $target->save(false);
                $token[] = "set {$target->label}:{$storeManager->showMoney($target->local_amount)}";
                $allOrderFees['packing_fee'] = $target;
            } else if (($target = $allOrderFees['packing_fee']) instanceof TargetAdditionalFee && !WeshopHelper::compareValue($target->amount, $packingWood, 'float')) {
                $target->amount = $inspection;
                $target->local_amount = $localAmount;
                $token[] = "update {$target->label}:{$storeManager->showMoney($target->local_amount)}";
                $target->save(false);
                $allOrderFees['packing_fee'] = $target;
            }


        }

        if (($courier = Yii::$app->request->post('courier')) !== null && is_string($courier) && $courier !== '') {
            $courier = json_decode($courier, true);
            if ((!isset($allOrderFees['international_shipping_fee']) || ($intFee = $allOrderFees['international_shipping_fee']) === null) && is_array($courier) && isset($courier['total_fee']) && $courier['total_fee'] > 0) {
                $target = new TargetAdditionalFee();
                $target->name = 'international_shipping_fee';
                $target->type = 'local';
                $target->amount = $courier['total_fee'];
                $target->local_amount = $storeManager->roundMoney($courier['total_fee']);
                $target->discount_amount = 0;
                $target->currency = $storeManager->getCurrencyName();
                $target->label = $order->store_id === 1 ? 'Phí vận chuyển quốc tế' : 'International Shipping Fee';
                $target->created_at = $timeTimestamp;
                $target->remove = 0;
                $target->target = 'order';
                $target->target_id = $order->id;
                $target->save(false);
                $allOrderFees[$target->name] = $target;
            }
        }

        $totalFeeAmount = 0;
        foreach ($allOrderFees as $orderFee) {
            /** @var $orderFee TargetAdditionalFee */
            if ($orderFee->name === 'packing_fee') {
//                $value = $order->total_packing_fee_local ? $order->total_packing_fee_local : 0;
//                $value += $orderFee->local_amount;
//                $order->total_packing_fee_local = $value;
                $order->total_packing_fee_local = $orderFee->local_amount;
            } elseif ($orderFee->name === 'inspection_fee') {
//                $value = $order->total_inspection_fee_local ? $order->total_inspection_fee_local : 0;
//                $value += $orderFee->local_amount;
//                $order->total_inspection_fee_local = $value;
                $order->total_inspection_fee_local = $orderFee->local_amount;
            } elseif ($orderFee->name === 'insurance_fee') {
                $token[] = "set {$orderFee->label}:{$storeManager->showMoney($orderFee->local_amount)}";
//                $value = $order->total_insurance_fee_local ? $order->total_insurance_fee_local : 0;
//                $value += $orderFee->local_amount;
//                $order->total_insurance_fee_local = $value;
                $order->total_insurance_fee_local = $orderFee->local_amount;
            } elseif ($orderFee->name === 'international_shipping_fee') {
                $token[] = "set {$orderFee->label}:{$storeManager->showMoney($orderFee->local_amount)}";
                $order->total_intl_shipping_fee_local = $orderFee->local_amount;
            } elseif ($orderFee->name === 'product_price') {
                continue;
            }
            $totalFeeAmount += $orderFee->local_amount;

        }

        if ($courier !== null && is_array($courier) && !empty($courier)) {
            $order->courier_service = isset($courier['service_code']) ? $courier['service_code'] : null;
            $order->courier_name = implode(' ', [$courier['courier_name'], $courier['service_name']]);
            $order->courier_delivery_time = implode(' ', [$courier['min_delivery_time'], $courier['max_delivery_time']]);
        }
        $order->total_fee_amount_local = $totalFeeAmount;
        $order->total_final_amount_local = $order->total_amount_local + $order->total_fee_amount_local;

        $paidPercent = round(($order->total_paid_amount_local / $order->total_final_amount_local) * 100);

        $order->current_status = Order::STATUS_AWAITING_PAYMENT;

        if ($order->total_paid_amount_local >= 0 && $paidPercent > 70) {
            $order->current_status = Order::STATUS_READY2PURCHASE;
        }
        if (in_array($order->current_status, [Order::STATUS_READY2PURCHASE, Order::STATUS_CONTACTING, Order::STATUS_AWAITING_PAYMENT])) {
            if ($order->current_status == Order::STATUS_READY2PURCHASE) {
                if ($order->contacting == null) {
                    $order->contacting = $timeTimestamp;
                }
                if ($order->awaiting_payment == null) {
                    $order->awaiting_payment = $timeTimestamp;
                }
                $order->ready_purchase = $timeTimestamp;
            }
        }
        $token[] = "status {$order->current_status}";
        $dirtyAttributes = $order->getDirtyAttributes();
        $messages = "<span class='text-danger font-weight-bold'>Order {$order->ordercode}</span> <br> - Confirm <br> {$this->resolveChatMessage($dirtyAttributes,$order)}";
        $order->update(false);
        ChatHelper::push($messages, $order->ordercode, 'GROUP_WS', 'SYSTEM', null);
        return $this->response(true, implode(',', $token));
    }

    public function actionExport()
    {
        $params = Yii::$app->request->get();
        $response = (new Order())->searchExport($params);
        ArrayHelper::removeValue($response, 'products');
        $url = ExcelHelper::write($response);

        return $this->response(true, "action not support", $url);
    }

    /**
     * @param $condition
     * @param bool $with
     * @return null|Order
     * @throws NotFoundHttpException
     */
    protected function findModel($condition, $with = true)
    {
        $query = Order::find();
        if ($with === true) {
            $query->withFullRelations();
        }
        if (is_numeric($condition)) {
            $condition = [$query->getColumnName('id') => $condition];
        }
        $query->where($condition);
        if (($model = $query->one()) === null) {
            throw new NotFoundHttpException("Not found order");
        }
        return $model;
    }

    /**
     * @param $dirtyAttributes
     * @param $reference \common\components\db\ActiveRecord
     * @return string
     */
    protected function resolveChatMessage($dirtyAttributes, $reference)
    {

        $results = [];
        foreach ($dirtyAttributes as $name => $value) {
            if (strpos($name, '_id') !== false && is_numeric($value)) {
                continue;
            }
            $results[] = "<span class='font-weight-bold'>- {$reference->getAttributeLabel($name)} :</span> <br> Changed from `{$reference->getOldAttribute($name)}` to `$value`";
        }

        return implode('<br> ', $results);
    }


}
