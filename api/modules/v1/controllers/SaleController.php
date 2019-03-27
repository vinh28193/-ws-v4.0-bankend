<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 3/26/2019
 * Time: 4:12 PM
 */

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\BaseApiController;
use common\models\Order;

class SaleController extends BaseApiController
{
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true),
            ],
            [
                'allow' => true,
                'actions' => ['assign'],
                'roles' => ['sale', 'master_sale'],
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'assign' => ['PUT','POST']
        ];
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => \common\actions\SaleAction::className()
        ]);
    }

    public function actionAssign($id)
    {
        if(($model = Order::findOne($id)) === null){
            return $this->response(false,"not found order $id");
        }
        $model->setScenario(Order::SCENARIO_SALE_ASSIGN);
        /** @var  $identity  \common\models\User */
        $identity = Yii::$app->getUser()->getIdentity();
        $model->sale_support_id = $identity->getId();
        $model->support_email = $identity->email;
        if(!$model->save()){
            return $this->response(false, $model->getFirstErrors());
        }
        return $this->response(true, "sale {$identity->email} assign to order $id",$identity->getPublicIdentity());
    }
}