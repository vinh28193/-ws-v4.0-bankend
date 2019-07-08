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
use common\helpers\ChatHelper;
use common\models\Order;
use common\models\Product;
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
                'actions' => ['index', 'view', 'create', 'update'],
                'roles' => $this->getAllRoles(true,'marketing'),
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
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE']
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
            $query = Order::find();
            $request = $query->where([$query->getColumnName('ordercode') => $code])
                ->withFullRelations()
                ->asArray()->all();
            return $this->response(true, "Get order $code success", $request);
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
            'supporting',
            'supported',
            'ready_purchase',
            'purchase_start',
            'purchased',
            'seller_shipped',
            'stockin_us',
            'stockout_us',
            'stockin_local',
            'stockout_local',
            'at_customer',
            'returned',
            'cancelled',
            'lost',
        );
        $user = Yii::$app->user->identity;
        $post = Yii::$app->request->post();
        $now = Yii::$app->getFormatter()->asTimestamp('now');
        $model = Order::findOne($id);
        //$this->can('canUpdate', $model);
        $check = $model->loadWithScenario($this->post);
        if ($model->getScenario() == 'confirmPurchase') {
            $product_id = Yii::$app->request->post('product_id');
            $tran = Yii::$app->db->beginTransaction();
            $status = Product::STATUS_SUPPORTED;
            if($model->total_paid_amount_local >= 0 && $model->total_paid_amount_local >= $model->total_final_amount_local){
                $status = Product::STATUS_READY2PURCHASE;
            }
//            foreach ($model->products as $product){
//                if($product_id){
//                    if($product->id == $product_id){
//                        if($product->custom_category_id){
//                            $product->current_status = $status;
//                            $product->save(0);
//                        }else{
//                            $tran->rollBack();
//                            return $this->response(false,'Product id '.$product->id.' hasn\'t  category policy!');
//                        }
//                    }
//                }else{
//                    if($product->custom_category_id){
//                        $product->current_status = $status;
//                        $product->save(0);
//                    }else{
//                        $tran->rollBack();
//                        return $this->response(false,'Product id '.$product->id.' hasn\'t  category policy!');
//                    }
//                }
//            }
            if(in_array($model->current_status,[Order::STATUS_READY2PURCHASE,Order::STATUS_NEW,Order::STATUS_SUPPORTED,Order::STATUS_SUPPORTING])){
                $model->current_status = $status;
                if($status == Order::STATUS_READY2PURCHASE){
                    $model->ready_purchase = time();
                }
            }
            $tran->commit();
//            if (isset($post['Order']['checkR2p'])) {
//                if ($post['Order']['checkR2p'] == 'yes') {
//                    for ($i = 0; $i < 4; $i++) {
//                        if ($model->{$StatusOrder[$i]} == null) {
//                            $model->{$StatusOrder[$i]} = $now;
//                        }
//                    }
//                    $model->current_status = Order::STATUS_READY2PURCHASE;
//                } else if ($post['Order']['checkR2p'] != 'yes') {
//                    for ($i = 0; $i < 3; $i++) {
//                        if ($model->{$StatusOrder[$i]} == null) {
//                            $model->{$StatusOrder[$i]} = $now;
//                        }
//                    }
//                    $model->current_status = Order::STATUS_SUPPORTED;
//                }
//            }
        }
        if ($model->getScenario() == 'updateOrderStatus') {
            for ($i = 0; $i < (int)($post['Order']['status']); $i++) {
                if ($model->{$StatusOrder[$i]} == null) {
                    $model->{$StatusOrder[$i]} = $now;
                }
            }
            for ($j = (int)($post['Order']['status']) + 1; $j < 15 ; $j++) {
                $model->{$StatusOrder[$j]} = null;
            }
//            $model->current_status = strtoupper($post['Order']['current_status']);
            if ($StatusOrder[(int)($post['Order']['status']) - 1] == 'purchase_start') {
                $model->current_status = Order::STATUS_PURCHASING;
            }
            if ($StatusOrder[(int)($post['Order']['status']) - 1] == 'stockin_us') {
                $model->current_status = Order::STATUS_STOCK_IN_US;
            }
            if ($StatusOrder[(int)($post['Order']['status']) - 1] == 'stockout_us') {
                $model->current_status = Order::STATUS_STOCK_OUT_US;
            }
            if ($StatusOrder[(int)($post['Order']['status']) - 1] == 'stockin_local') {
                $model->current_status = Order::STATUS_STOCK_IN_LOCAL;
            }
            if ($StatusOrder[(int)($post['Order']['status']) - 1] == 'stockout_local') {
                $model->current_status = Order::STATUS_STOCK_OUT_LOCAL;
            }
            if ($StatusOrder[(int)($post['Order']['status']) - 1] == 'ready_purchase') {
                $model->current_status = Order::STATUS_READY2PURCHASE;
            } else {
                $model->current_status = strtoupper($StatusOrder[(int)($post['Order']['status']) - 1]);
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
            $i = 0;
            for ($i; $i<15; $i++) {
                if ($StatusOrder[$i] == $post['Order']['column']) {
                    break;
                }
            }
            $j = $i;
            for ($j; $j<15; $j++) {
                $model->{$StatusOrder[$j]} = null;
            }
            if ($post['Order']['column'] == 'ready_purchase') {
                $model->current_status = 'READY2PURCHASE';
            } else {
                $model->current_status = strtoupper(str_replace('_', ' ', $StatusOrder[$i-1]));
            }
        }
//        if (($model->current_status == Order::STATUS_NEW || $model->current_status == Order::STATUS_SUPPORTING || $model->current_status == Order::STATUS_SUPPORTED) && $model->total_paid_amount_local > 0) {
//            $model->current_status =  Order::STATUS_READY2PURCHASE;
//            $model->ready_purchase = $now;
//        }
        $dirtyAttributes = $model->getDirtyAttributes();
        $action = Inflector::camel2words($model->getScenario());
        Yii::info([$dirtyAttributes, $model->getOldAttributes()], $model->getScenario());
        if ($model->getScenario() == 'editAdjustPayment') {
            $messages = "order {$model->ordercode} $action {$this->resolveChatMessage($dirtyAttributes,$model)}, update Payment Transaction: `Transaction Status` changed from `CREATE` to `SUCCESS`";
        } else {
            $messages = "order {$model->ordercode} $action {$this->resolveChatMessage($dirtyAttributes,$model)}";
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
            ChatHelper::push($messages, $model->ordercode, 'GROUP_WS', 'SYSTEM', $post['Order']['link_image_log'] ? $post['Order']['link_image_log'] :  null);
        } else {
            ChatHelper::push($messages, $model->ordercode, 'GROUP_WS', 'SYSTEM', null);
        }
        Yii::info("Order Log ".$model->getScenario());
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
            $results[] = "`{$reference->getAttributeLabel($name)}` changed from `{$reference->getOldAttribute($name)}` to `$value`";
        }

        return implode(", ", $results);
    }


}
