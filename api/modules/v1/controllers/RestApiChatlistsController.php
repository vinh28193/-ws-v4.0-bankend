<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\RestApiCall;
use Yii;
use common\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
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

    	$filename = 'chat-vi.json';
    	$content  = $this->readFileChat($filename);
        return $this->response(true, 'Success', $content);
    }

    public function actionCreate()
    {

    	$filename = 'chat-vi.json';
    	$content = (isset($_POST['content'])) ?  $_POST['content'] : 'null';
    	$content_file  = $this->writeFileChat($content,$filename);
        return $this->response(true, 'Success', $content_file);
    }

    public function actionUpdate($id)
    {
        
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->can('canDelete', ['id' => $model->id]);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }


    protected function writeFileChat($content,$filename)
    {
        
    	$path = Yii::getAlias('@webroot/listchats/'.$filename);
        $tempArray = $this->readFileChat($filename);
        $content     = mb_strtolower($content);
        if(empty($tempArray))
        {
         $tempArray = array();  
        }
        array_push($tempArray, $content);

        $jsonData = json_encode($tempArray);

        file_put_contents($path, $jsonData);

        return $tempArray;

    }

    protected function readFileChat($filename)
    {
    	$path = Yii::getAlias('@webroot/listchats/'.$filename);
        $content = file_get_contents($path);
        $listchats = json_decode($content, true);
        return $listchats;
    }

    protected function deleteContentFileChat($key,$filename)
    {
    	$path = Yii::getAlias('@webroot/listchats/'.$filename);
        $content = file_get_contents($path);
        $listchats = json_decode($content, true);
        return $listchats;
    }

    protected function checkStringInFile($string,$file)
    {
               $return = false;
               if(in_array($string, $file)) $return = true;
               return $return;
    }
}
