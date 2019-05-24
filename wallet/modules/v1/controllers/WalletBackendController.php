<?php


namespace wallet\modules\v1\controllers;


use wallet\controllers\BackendController;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\form\TransactionForm;
use wallet\modules\v1\models\WalletClient;
use Yii;

class WalletBackendController extends BackendController
{
    public function actionIndex(){
        return $this->response(true);
    }

    /**
     * Param gồm
     * type , transaction_code , total_amount , payment_method , payment_provider , bank_code , customer_id , description
     * @return mixed
     */
    public function actionCreateTransactionAddFee(){
        $wallet_C = WalletClient::findOne(['customer_id' => Yii::$app->request->post('customer_id') , 'status' => WalletClient::STATUS_ACTIVE]);
        if($wallet_C){
            $form = new TransactionForm();
            if ($form->load($this->post)) {
                $form->setWalletClient($wallet_C);
                $form->description = Yii::$app->request->post('description');
                $result = $form->makeSafeTransaction();
                $success = isset($result['success']) ? $result['success'] : isset($result['code']) && $result['code'] == ResponseCode::SUCCESS;
                return $this->response($success, $result['message'], $result['data'], $result['code']);
            }
            return  $this->response(false, Yii::t('wallet', 'Transaction not found'), null, ResponseCode::NOT_FOUND);
        }
        return  $this->response(false, Yii::t('wallet', 'Cannot find wallet client'), null, ResponseCode::NOT_FOUND);
    }
}