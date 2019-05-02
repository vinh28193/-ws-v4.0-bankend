<?php


namespace api\controllers;


class CallbackController extends \yii\rest\Controller
{

    public function actions()
    {
        return array_merge(parent::actions(),[
            'update-item' => \common\boxme\BoxmeCallBackAction::className()
        ]);
    }
}