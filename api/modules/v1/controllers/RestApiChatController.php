<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\components\RestApiCall;
use common\components\ChatMongoWs;
use common\models\Order;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class RestApiChatController extends BaseApiController
{
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update'],
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

    public function actionIndex()
    {
        $response = ChatMongoWs::search($params = '');
        return $this->response(true, 'Success', $response);
    }

    public function actionCreate()
    {
        $_post = (array)$this->post;
        if (isset($_post) !== null) {
            @date_default_timezone_set('Asia/Ho_Chi_Minh');
            $model = new ChatMongoWs();
            $model->attributes = $_post;
            $_user_Identity = Yii::$app->user->getIdentity();
            $_user_id = $_user_Identity->getId();
            $_user_email = $_user_Identity['email'];
            $_user_AuthKey = $_user_Identity->getAuthKey();
            $_user_name = $_user_Identity['username'];
            //----ToDo Need More Infor param
            $_user_app = 'Weshop2019';
            $_user_request_suorce = "BACK_END"; // "APP/FRONTEND/BACK_END" // Todo : set
            $_request_ip = Yii::$app->getRequest()->getUserIP();

            $_rest_data = ["ChatMongoWs" => [
                "success" => true,
                "message" => @json_encode($_post['message']),
                "date" => date('Y-m-d H:i:s'),
                "user_id" => $_user_id,
                "user_email" => $_user_email,
                "user_name" => $_user_name,
                "user_app" => $_user_app,
                "user_request_suorce" => $_user_request_suorce,
                "request_ip" => $_request_ip, // Todo : set
                "user_avatars" => null,
                "Order_path" => $_post['Order_path'],
                "is_send_email_to_customer" => null,
                "type_chat" => $_post['type_chat'], //'WS_CUSTOMER',   // Todo : set
                "is_customer_vew" => null,
                "is_employee_vew" => null
            ]];

            if ($model->load($_rest_data) and $model->save()) {
                $id = (string)$model->_id;
                return $this->response(true, 'Success', $response = $model->attributes);
            } else {
                Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
            }

        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
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
            $response = ChatMongoWs::find()
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
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    protected function findModel($id)
    {
        if (($model = ChatMongoWs::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }
}
