<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 3/23/2019
 * Time: 10:19 AM
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\SystemCountry;
use Yii;

class SystemStateProvinceController extends BaseApiController
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
            'index' => \common\actions\SystemLocationAction::className()
        ]);
    }

}