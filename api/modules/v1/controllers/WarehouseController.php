<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use yii\helpers\ArrayHelper;

class WarehouseController extends BaseApiController
{

    protected function rules()
    {
        return [
            [
                'allow' => true,
                'roles' => $this->getAllRoles(true),
            ]
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['get']
        ];
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(),[
            'index' => 'common\actions\WarehouseAction'
        ]);
    }
}