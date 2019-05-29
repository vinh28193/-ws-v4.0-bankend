<?php
namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\BoxmeLogInspectionWs ;
use common\models\Order;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class RestBmLogInspectWsController extends BaseApiController
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
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
            'group-viewed' => ['POST'],
            'customer-viewed' => ['POST']
        ];
    }

    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $query = BoxmeLogInspectionWs::find();
        if (isset($get['ordercode'])) {
            $query->andWhere(['id' => $get['ordercode']]);
        }
        if (isset($get['content'])) {
            $query->andWhere(['like', 'data_input', $get['content']]);
        }
        if (isset($get['ip'])) {
            $query->andWhere(['request_ip' => $get['ip']]);
        }
        if (isset($get['user_name'])) {
            $query->andWhere(['user_name' => $get['user_name']]);
        }
        if ((isset($get['startTime']) && isset($get['endTime']))) {
            $query->andWhere(['between', 'created_at', (int)Yii::$app->formatter->asTimestamp($get['startTime']), (int)Yii::$app->formatter->asTimestamp($get['endTime'])]);
        }
        if ($get['valueCreate'] == 0) {
            $model = $query->orderBy(['created_at' => SORT_DESC]);
        }
        if ($get['valueCreate'] == 1) {
            $model = $query->orderBy(['created_at' => SORT_ASC]);
        }
        $total = $model->count();
        $limit = isset($get['limit']) ? $get['limit'] : 10;
        $page = isset($get['page']) ? $get['page'] : 1;
        $offset = ($page - 1) * $limit;
        $model->limit($limit)->offset($offset);
        $query = $model->asArray()->all();
        $data = [
            'model' => $query,
            'totalCount' => $total
        ];
        return $this->response(true, "success", $data);
    }

    public function actionCreate()
    {
        $_post = (array)$this->post;
        if (isset($_post) !== null) {
            $model = new BoxmeLogInspectionWs();
            $model->attributes = $_post;
            $_user_Identity = Yii::$app->user->getIdentity();
            $_user_id = $_user_Identity->getId();
            $_user_email = $_user_Identity['email'];
            $_user_AuthKey = $_user_Identity->getAuthKey();
            $_user_name = $_user_Identity['username'];
            //----ToDo Need More Infor param
            $_user_app = 'Weshop2019'; // Todo : set
            $_request_ip = Yii::$app->getRequest()->getUserIP();

            $_rest_data = ["BoxmeLogInspectionWs" => [

                //User : Who ai tác
                "user_id" => $_user_id,
                "user_email" => $_user_email,
                "user_name" => $_user_name,
                "user_avatar" => null,
                "Role" =>  $_post['role'],

                //Action thao tác là gì ?
                "action_path" => $_post['action_path'],
                "LogTypeInspection" =>  $_post['LogTypeInspection'], // "Order hoăc Product", // LogType : Order | Product : and Id để join
                "id" => $_post['id'], //"Id để join với Logtype nêu là Order hoặc nếu là Product",

                'status' => $_post['status'], 'Trạng thái nhãn kiểm : DONE , .....',

                // data
                "data_input" => is_array($_post['data_input']) ? @json_encode($_post['data_input']) : $_post['data_input'] ,   // dữ liệu ban đầu trước khi ghi log
                "data_output" => is_array($_post['data_output']) ? @json_encode($_post['data_output']) : $_post['data_output'] , // dữ liệu sau khi xử lý

                // time
                //"created_at" => "created_at", "updated_at" => "updated_at",
                "date" => Yii::$app->getFormatter()->asDatetime('now'),

                // ENV nào bắn lên
                "user_app" => $_user_app,
                "user_request_suorce" => $_post['suorce'],  // "APP/FRONTEND/BACK_END"
                "request_ip" => $_request_ip,

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
            $response = BoxmeLogInspectionWs::find()
                ->where(['id' => $id])
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
        if (($model = BoxmeLogInspectionWs::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }
}
