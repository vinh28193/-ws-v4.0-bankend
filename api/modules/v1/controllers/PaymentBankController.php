<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 7/5/2019
 * Time: 1:28 PM
 */

namespace api\modules\v1\controllers;
use api\controllers\BaseApiController;
use common\models\db\PaymentBank;
use Yii;


class PaymentBankController extends BaseApiController
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
                'actions' => ['index', 'view', 'update'],
                'roles' => ['master_operation', 'tester', 'master_sale', 'sale', 'master_accountant', 'accountant']
            ],
        ];
    }
    public function actionIndex() {
        $model = PaymentBank::find()->select(['id', 'name', 'code'])->asArray()->all();
        return $this->response(true, 'success', $model);
    }
}