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

    public function actionSubscribe()
    {
        $_post = (array)$this->post;
        $_user_Identity = Yii::$app->user->getIdentity();
        $user_id = $_user_Identity->getId();
        $_user_email = $_user_Identity['email'];
        $_user_AuthKey = $_user_Identity->getAuthKey();
        $_user_name = $_user_Identity['username'];
        $token = $_post['token'];
        $fingerprint = $_post['fingerprint'];
        $details = $_post['details'];
        $ordercode = $_post['ordercode'];
        $nv = $_post['nv'];
        $date_now = Yii::$app->formatter->asDateTime('now');

        $order_item = [
            'code' => $ordercode,
            'subscribed_on' => $date_now
        ];

        $_rest_data = ["PushNotifications" => [
            'user_id' => $user_id,
            'user_email' => $_user_email,
            'user_name' => $_user_name,
            'order_list' => array($ordercode => $order_item),
            // Infor Field Notification
            'token' => $token,
            'fingerprint' => $fingerprint,
            'nv' => $nv,
            'details' => $details
        ]];

        $query = PushNotifications::find()
            ->where(['fingerprint' => $fingerprint])
            ->one();
        $order_list = [];

        if (!empty($query)) {

            $order_list = $query->order_list;
            if (!array_key_exists($ordercode, $order_list)) {

                $order_list[$ordercode] = array(
                    'code' => $ordercode,
                    'subscribed_on' => $date_now
                );
                $query->order_list = $order_list;
                if ($query->save()) ;
                {
                    return $this->response(true, 'Success', $query);
                }
                return $this->response(false, 'Error delete ordercode');

            }
        } else {
            $model = new PushNotifications();
            if ($model->load($_rest_data) and $model->save()) {
                return $this->response(true, 'Success', $_rest_data);
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

    public function actionDelete($id)
    {
        $_post = (array)$this->get;

        $fingerprint = (int)$_post['id'];
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
