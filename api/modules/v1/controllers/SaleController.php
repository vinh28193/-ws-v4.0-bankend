<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 3/26/2019
 * Time: 4:12 PM
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;

class SaleController extends BaseApiController
{
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true),

            ]
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
        ];
    }

    public function actions()
    {
        return array_merge(parent::actions(),[
            'index' => \common\actions\SaleAction::className()
        ]);
    }
}