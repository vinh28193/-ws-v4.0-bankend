<?php


namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\logs\PackingLogs;
use common\logs\TrackingLogs;
use yii\helpers\ArrayHelper;

class LogTrackingController extends BaseApiController
{
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['view-log'],
                'roles' => $this->getAllRoles(true),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'view-log' => ['POST'],
        ];
    }
    public function actionViewLog(){
        $type =ArrayHelper::getValue($this->post,'type');
        $code =ArrayHelper::getValue($this->post,'code');
        $data = [];
        switch ($type){
            case 'tracking':
                $data = TrackingLogs::GetLogTracking($code);
                break;
            case 'package':
                $data = PackingLogs::GetLogTracking($code);
                break;
        }
        return $this->response(true,'Get Log Success', $data);
    }
}