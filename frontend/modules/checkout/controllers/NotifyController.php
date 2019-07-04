<?php


namespace frontend\modules\checkout\controllers;


class NotifyController extends CheckoutController
{

    public function actionOfficeSuccess($code)
    {
        return $this->render('office_success');
    }

    public function actionNicePaySuccess($code)
    {
        $code = $this->request->get('code', $code);
        $token = $this->request->get('token', '');
        $billingNm = $this->request->get('billingNm', '');
        $transTm = $this->request->get('transTm', '');
        $transDt = $this->request->get('transDt', '');
        $bankVacctNo = $this->request->get('bankVacctNo', '');
        $vacctValidDt = $this->request->get('vacctValidDt', '');
        $vacctValidTm = $this->request->get('vacctValidTm', '');
        $bankCd = $this->request->get('bankCd', '');
        $currency = $this->request->get('currency', $this->storeManager->getCurrencyName());
        $amount = (int)$this->request->get('amount', 0);
        $description =  $this->request->get('description', '');
        return $this->render('nice_pay_success', [
            'code' => $code,
            'token' => $token,
            'billingNm' => $billingNm,
            'transTm' => $transTm,
            'transDt' => $transDt,
            'bankVacctNo' => $bankVacctNo,
            'vacctValidDt' => $vacctValidDt,
            'vacctValidTm' => $vacctValidTm,
            'bankCd' => $bankCd,
            'currency' => $currency,
            'amount' => $amount,
            'description' => $description
        ]);
    }

    public function actionBankTransferSuccess($code)
    {
        return $this->render('bank-transfer-success', $this->request->get());
    }
}