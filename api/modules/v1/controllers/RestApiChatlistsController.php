<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\RestApiCall;
use Yii;
use common\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use api\modules\v1\controllers\service\ChatlistsServiceController;
use common\components\StoreManager;
class RestApiChatlistsController extends BaseApiController
{

    public $filename;
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

    public function init(){
        $obj_store = new StoreManager; 
        $domain_id = $obj_store->isVN();
        $domain_id = (int)$domain_id;
        $this->filename = 'chatsupport-in.json';
        if($domain_id == 1)
        {
           $this->filename = 'chatsupport-vi.json'; 
        }
        
    }
    public function actionIndex()
    {
    	$content  = ChatlistsServiceController::readFileChat($this->filename);
        return $this->response(true, 'Success', $content);
    }

    public function actionCreate()
    {
        
        $_post = (array)$this->post;

        if (isset($_post) !== null) {
   
            $content = (isset($_post['content'])) ?  $_post['content'] : 'null';
            $content_file  = ChatlistsServiceController::writeFileChat($content,$this->filename);
            return $this->response(true, 'Success', $content_file);
        }

    }


    public function actionDelete($id)
    {

        if($id != null)
        {
            $key = $id;
            
            $content_file = ChatlistsServiceController::removeKeyFileChat($key,$this->filename);
            $this->can('canDelete', ['key' => $key]);
            return $this->response(true, 'Success', $content_file);  
        }

        
    }

}
