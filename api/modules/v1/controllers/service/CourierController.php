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
        var_dump($bodyParams);die;
//        $form = new CalculateForm();
//        if(!$form->load($bodyParams, '')){
//            return $this->response(false, 'can not resolve current parameter', []);
//        }
//        list($success, $message, $data) = $form->calculate();
//        return $this->response($success, $message, $data);
    }

    public function actionCancel($code)
    {

    }
}