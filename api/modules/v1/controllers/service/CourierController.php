<?php


namespace api\modules\v1\controllers\service;

use Yii;
use common\boxme\forms\CalculateForm;
use common\boxme\forms\CreateOrderForm;
use api\controllers\BaseApiController;

class CourierController extends BaseApiController
{

    protected function rules()
    {
        return [
            [
                'allow' => true,
            ]
        ];
    }

    protected function verbs()
    {
        return [
            'create' => ['POST'],
            'calculate' => ['POST'],
            'cancel' => ['GET']
        ];
    }

    public function actionCreate()
    {

    }

    public function actionCalculate()
    {
        $bodyParams = Yii::$app->request->bodyParams;
        $form = new CalculateForm();
        if (!$form->load($bodyParams, '')) {
            return [
                'error' => true,
                'error_code' => 'Error Validate',
                'messages' => $form->getFirstErrors(),
                'data' => []
            ];
        }
        return $form->calculate();
    }

    public function actionCancel($code)
    {

    }
}