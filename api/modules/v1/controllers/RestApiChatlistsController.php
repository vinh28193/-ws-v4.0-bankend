<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\RestApiCall;
use Yii;
use common\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use api\modules\v1\controllers\service\ChatlistsServiceController;
class RestApiChatlistsController extends BaseApiController
{


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
        ];
    }

    public function actionIndex()
    {
   
    	$filename = 'chatsupport-vi.json';
    	$content  = ChatlistsServiceController::readFileChat($filename);
        return $this->response(true, 'Success', $content);
    }

    public function actionCreate()
    {
        $_post = (array)$this->post;
        if (isset($_post) !== null) {
            $filename = 'chatsupport-vi.json';
            $content = (isset($_post['content'])) ?  $_post['content'] : 'null';
            $content_file  = ChatlistsServiceController::writeFileChat($content,$filename);
            return $this->response(true, 'Success', $content_file);
        }

    }


    public function actionDelete($id)
    {

        if($id != null)
        {
            $key = $id;
            $filename = 'chatsupport-vi.json';
            $content_file = ChatlistsServiceController::removeKeyFileChat($key,$filename);
            $this->can('canDelete', ['key' => $key]);
            return $this->response(true, 'Success', $content_file);  
        }

        
    }

}
