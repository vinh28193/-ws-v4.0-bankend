<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\models\Manifest;
use common\modelsMongo\RequestGetDetailBoxMe;

class ManifestBoxMeController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['get-detail'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'get-detail' => ['POST'],
        ];
    }

    public function actionGetDetail($manifest_id){
        $manifest = Manifest::findOne($manifest_id);
        if(!$manifest){
            return $this->response(false,"Can not to find anything manifest with manifest_id : ".$manifest_id);
        }
        $request_get = RequestGetDetailBoxMe::find()
            ->where(['manifest_code' => $manifest->manifest_code,'manifest_id' => $manifest->id])
            ->one();
        if(!$request_get){
            $request_get = new RequestGetDetailBoxMe();
            $request_get->manifest_id = $manifest_id;
            $request_get->manifest_code = $manifest->manifest_code;
            $request_get->count_request = 0;
            $request_get->created_by = \Yii::$app->user->getIdentity()->username.'-'.\Yii::$app->user->id;
            $request_get->created_at = date('Y-m-d H:i:s');
        }
        $mess = "Set job get detail manifest box me success with manifest_id : ".$manifest_id;
        if($request_get->status == RequestGetDetailBoxMe::STATUS_NEW){
            return $this->response(TRUE,"Manifest were ready get detail. Please wait some minute!");
        }else if($request_get->status == RequestGetDetailBoxMe::STATUS_PROCESSING){
            return $this->response(TRUE,"Manifest were processing get detail. Please wait some minute!");
        }else if($request_get->status == RequestGetDetailBoxMe::STATUS_PROCESSING){
            return $this->response(TRUE,"Manifest were processing get detail. Please wait some minute!");
        }else{
            $mess .= $request_get->status ? ". Change status from ".$request_get->status." to ".RequestGetDetailBoxMe::STATUS_NEW : ".";
        }
        $request_get->status = RequestGetDetailBoxMe::STATUS_NEW;
        $request_get->updated_by = \Yii::$app->user->getIdentity()->username.'-'.\Yii::$app->user->id;
        $request_get->updated_at = date('Y-m-d H:i:s');
        return $this->response(TRUE,$mess);
    }
}