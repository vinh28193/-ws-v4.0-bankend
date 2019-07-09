<?php


namespace frontend\modules\payment\controllers;


use common\models\logs\PaymentGatewayLogs;

class CallbackController extends BasePaymentController
{

    public function actionAlepay()
    {
        $bodyParams = $this->request->bodyParams;
        $logCallback = new PaymentGatewayLogs();
        $logCallback->response_time = date('Y-m-d H:i:s');
        $logCallback->create_time = date('Y-m-d H:i:s');
        $logCallback->type = PaymentGatewayLogs::TYPE_CHECK_PAYMENT;
        $logCallback->store_id = 1;
        $logCallback->response_content = $bodyParams;
        $logCallback->transaction_code_request = "ALEPAY Check PAYMENT";
        $logCallback->save(false);
        return $this->response(true,'success');
    }
}