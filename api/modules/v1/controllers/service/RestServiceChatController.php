<?php

namespace api\modules\v1\controllers\service;

use Yii;
use api\modules\v1\controllers\RestApiChatController as RestApiChat;
use app\models\User;

class RestServiceChatController extends RestApiChat
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
                'actions' => ['customer-viewed','group-viewed'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
            [
                'allow' => true,
                'actions' => ['customer-viewed','group-viewed'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canCreate']
            ],
        ];
    }

    public function verbs()
    {
        return [
            'group-viewed' => ['PATCH'],
            'customer-viewed' => ['PUT']
        ];
    }


    /**
     * @param : Update những User id nào đã xem qua đoạn text log chat.
    **/
    public function actionCustomerViewed($id)
    {
        if ($id !== null) {
            $model = $this->findModel($id);
            $model->attributes = $this->post;
            if ($model->save(false,['is_customer_vew'])) {
                Yii::$app->api->sendSuccessResponse($model->attributes);
            } else {
                Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    /**
     * @param : Update những User id  Thuộc Group Weshop nào đã xem qua đoạn text log chat.
     **/
    public function actionGroupViewed($id)
    {
        if ($id !== null) {
            $model = $this->findModel($id);
            $model->attributes = $this->post;
            if ($model->save(false,['is_employee_vew'])) {
                Yii::$app->api->sendSuccessResponse($model->attributes);
            } else {
                Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }



}
