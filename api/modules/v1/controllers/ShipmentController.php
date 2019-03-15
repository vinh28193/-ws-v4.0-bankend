<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 20:41
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;

class ShipmentController extends BaseApiController
{

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true, ['user', 'sale', 'marketing'])
            ],
        ];
    }

    public function actionIndex(){

    }
}