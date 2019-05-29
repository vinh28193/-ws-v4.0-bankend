<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\PushNotifications;
use common\models\Order;
use Yii;
use yii\caching\DbDependency;

//use common\data\ActiveDataProvider;
//use common\helpers\ChatHelper;
//use yii\web\NotFoundHttpException;
//use yii\web\ServerErrorHttpException;
//use common\modelsMongo\RestApiCall;

use yii\helpers\ArrayHelper;
use common\models\User;


class NotificationsController extends BaseApiController
{
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['pageCache'] = [
//            'class' => 'yii\filters\PageCache',
//            'only' => ['index'],
//            'duration' => 24 * 3600 * 365, // 1 year
//            'dependency' => [
//                'class' => 'yii\caching\DbDependency',
//                'dependencies' => [
//                    new DbDependency(['sql' => 'SELECT MAX(id) FROM ' . PushNotifications::tableName()])
//                ]
//            ],
//        ];
//        return $behaviors;
//    }

    /** Role :
     * case 'cms':
     * case 'warehouse':
     * case 'operation':
     * case 'sale':
     * case 'master_sale':
     * case 'master_operation':
     * case 'superAdmin' :
     **/
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'subscribe', 'update'],
                'roles' => $this->getAllRoles(true),

            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
            [
                'allow' => true,
                'actions' => ['subscribe'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canCreate']
            ],
            [
                'allow' => true,
                'actions' => ['update', 'delete'],
                'roles' => $this->getAllRoles(true, ['user', 'cms', 'warehouse', 'operation', 'master_sale', 'master_operation']),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'subscribe' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex()
    {
        $response = PushNotifications::search($params = '');
        return $this->response(true, 'Success', $response);
    }

    /**
     * @return array
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * Tables push_notifications Lưu toàn bộ thông tin User + Thiết Bị( token) Nhận Notification --> nhận theo dõi đơn nào
     *
     */
    public function actionSubscribe()
    {
        $post = (array)$this->post;
        $_user_Identity = Yii::$app->user->getIdentity();
        $user_id = $_user_Identity->getId();
        $_user_email = $_user_Identity['email'];
        $_user_name = $_user_Identity['username'];
        $token = ArrayHelper::getValue($post,'token');
        $fingerprint = ArrayHelper::getValue($post,'fingerprint');
        $details = ArrayHelper::getValue($post,'details');
        $ordercode = ArrayHelper::getValue($post,'ordercode');
        $nv = ArrayHelper::getValue($post,'nv');

        $date_now = Yii::$app->formatter->asDateTime('now');

        if($token== '' || empty($token)){
            return $this->response(false, 'Invalid token Record requested');
        }

        $order_item = [
            'code' => $ordercode,
            'subscribed_on' => $date_now
        ];

        $_rest_data = ["PushNotifications" => [
            'user_id' => $user_id,
            'user_email' => $_user_email,
            'user_name' => $_user_name,
            'order_list' => array($ordercode => $order_item),
            'token' => $token,  // Infor Field Notification Token ma dinh danh thiet bị để nhận Notification
            'fingerprint' => $fingerprint,
            'nv' => $nv,
            'details' => $details
        ]];

        if($token){
            Yii::info("Update Token FCM ");
            $User = new User();
            if( ($User = $User->findByTokenFcm($user_id,$token)) != null && !$User->token_fcm){
                Yii::info("Insert Token FCM : start ! ". $token);
                $User->token_fcm = $token;
                $User->id = $user_id;
                $User->last_token_fcm_time = Yii::$app->formatter->asDateTime('now');
                $User->last_token_fcm_by = 99999;
                if($User->update())
                {
                    Yii::info("Insert token_fcm : Ok ! ");
                }else {
                    Yii::info("Insert token_fcm : Error ! ");
                    Yii::info([
                        'Error' => $User->errors,
                    ], __CLASS__);
                }
            }
        }


        Yii::info("Data Notification Push");
        Yii::info([
            'user_id' => $user_id,
            'user_email' => $_user_email,
            'user_name' => $_user_name,
            'order_list' => array($ordercode => $order_item),
            'token' => $token,  // Infor Field Notification Token ma dinh danh thiet bị để nhận Notification
            'fingerprint' => $fingerprint,
            'nv' => $nv,
            'details' => $details
        ], __CLASS__);

        $query = PushNotifications::find()
            ->where(['fingerprint' => $fingerprint])
            ->one();
        $order_list = [];

        if (!empty($query)) {
            Yii::info("Update Next Token + User exits --> order ");
            $order_list = $query->order_list;   // Check Order Bin Code Exits
            if (!array_key_exists($ordercode, $order_list) and !empty($order_list)) {
                Yii::info("Check Order Bin Code No Exits");
                $order_list[$ordercode] = array(
                    'code' => $ordercode,
                    'subscribed_on' => $date_now
                );
                $query->order_list = $order_list;
                if ($query->save()){
                    return $this->response(true, 'Success save add date', $query);
                }else {
                    return $this->response(false, 'Error Save add data', []);
                }
            }else {
                Yii::info([
                    'order_list' => $order_list
                ], __CLASS__);
                Yii::info("Check OrderBinCode + Token Exits");
                return $this->response(true, 'Success', $order_list);
            }
        } else {
            Yii::info("Devices + User + BinCode New --> Save");
            $model = new PushNotifications();
            if ($model->load($_rest_data) and $model->save()) {
                return $this->response(true, 'Success', $_rest_data);
            } else {
                Yii::info("Devices + User + BinCode New --> Save : Fail Save New");
                return $this->response(false, 'Fail Save New');
            }
        }

    }

    public function actionUpdate($id)
    {
        if ($id !== null) {
            $model = $this->findModel($id);
            $model->attributes = $this->post;
            /***Todo -  Validate data model ***/
            if ($model->save()) {
                Yii::$app->api->sendSuccessResponse($model->attributes);
            } else {
                Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    public function actionView($id)
    {

        $_post = (array)$this->get;
        $fingerprint = (int)$_post['id'];
        $model = PushNotifications::find()
            ->where(['fingerprint' => $fingerprint])
            ->one();
        if (!empty($model)) {
            return $this->response(true, "Success", $model);
        } else {
            return $this->response(false, "Fingerprint not exists", $model);
        }
    }

    public function actionDelete($code)
    {
        $_post = (array)$this->get;

        $fingerprint = (int)$_post['code'];
        $ordercode = $_post['ordercode'];
        $model = PushNotifications::find()
            ->where(['fingerprint' => $fingerprint])
            ->one();

        if (empty($model)) {
            return $this->response(false, "Fingerprint not exists", $model);
        }

        $order_list = $model->order_list;
        $count = count($order_list);
        if ($count > 1) {

            // update notification
            if (array_key_exists($ordercode, $order_list)) {
                unset($order_list[$ordercode]);
                $model->order_list = $order_list;
                $model->save();
                return $this->response(true, "Delete success", $model);

            } else {
                return $this->response(false, "Ordercode not exists");

            }
        } else {
            // delete notification
            $model->delete();
            $model = ['order_list'=>0];
            return $this->response(true, "Delete success", $model);


        }

    }


    public function findModel($id)
    {
        if (($model = PushNotifications::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

}
