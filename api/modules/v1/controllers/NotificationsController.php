<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\RestApiCall;
use common\modelsMongo\PushNotifications;
use common\models\Order;
use Yii;
use common\data\ActiveDataProvider;
use common\helpers\ChatHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;


class NotificationsController extends BaseApiController
{
    /** Role :
        case 'cms':
        case 'warehouse':
        case 'operation':
        case 'sale':
        case 'master_sale':
        case 'master_operation':
        case 'superAdmin' :
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
                'roles' => $this->getAllRoles(true, ['user','cms', 'warehouse' ,'operation','master_sale','master_operation']),
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
          $details     = $_post['details'];
          $ordercode   = $_post['ordercode'];
          $nv          = $_post['nv'];
          $date_now = Yii::$app->formatter->asDateTime('now');

          $order_item = [
            'code' => $ordercode,
            'subscribed_on' => $date_now
          ];

          $_rest_data = ["PushNotifications" => [
            'user_id' => $user_id,
            'user_email' => $_user_email,
            'user_name' =>  $_user_name,
            'order_list'=> array($ordercode=>$order_item),
            // Infor Field Notification
            'token' => $token,
            'fingerprint' => $fingerprint,
            'nv' => $nv,
            'details' => $details
        ]];

        $query = PushNotifications::find()
            ->where(['fingerprint'=>$fingerprint])
            ->one();
        $order_list = [];

        if(!empty($query))
        {

            $order_list = $query->order_list; 
            if(!in_array($order_code, $order_list))
            {   

                $order_list[] = $order_code;
                $query->order_list = $order_list;
                $query->save();
                return $this->response(true, 'Success', $query);

            }
        }else
        {
            $model = new PushNotifications();
            if ($model->load($_rest_data) and $model->save())
            {
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
        
        if ($id !== null) {
            $response = PushNotifications::find()
                ->where(['Order_path' => $id])
                ->asArray()->all();
            return $this->response(true, "Get  $id success", $response);
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->can('canDelete', ['id' => $model->id]);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
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
