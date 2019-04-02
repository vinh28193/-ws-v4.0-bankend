<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\ActionLogWS as ActionLog;
use Yii;

class RestActionLogController extends BaseApiController
{
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
                'roles' => $this->getAllRoles(true, ['user', 'cms', 'warehouse', 'operation', 'master_sale', 'master_operation']),
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
        $response = ActionLog::search($params = '');
        return $this->response(true, 'Success', $response);
    }

    public function actionCreate()
    {
        $_post = (array)$this->post;
        $type = $_post['LogType'];
        unset($_post['LogType']);
        $_post['id'] = $type;
        $action = $_post['action_path'];
        unset($_post['action_path']);
        if (isset($_post['suorce'])) {
            $_post['user_request_suorce'] = $_post['suorce'];
            unset($_post['suorce']);
        }

        if(Yii::$app->wsLog->push($type === 'Product' ? 'product' : 'order',$action, null, $_post)){
            Yii::$app->api->sendSuccessResponse();
        }
        Yii::$app->api->sendFailedResponse("Invalid Record requested");

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
            $response = ActionLog::find()
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
        if (($model = ActionLogWS::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }
}
