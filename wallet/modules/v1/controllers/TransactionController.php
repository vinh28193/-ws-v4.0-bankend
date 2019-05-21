<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-09
 * Time: 11:08
 */

namespace wallet\modules\v1\controllers;

use wallet\modules\v1\models\form\ChangeBalanceForm;
use wallet\modules\v1\models\WalletClient;
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\WalletTransaction;
use wallet\modules\v1\models\form\TransactionForm;

/**
 * Class TransactionController
 * @package wallet\modules\v1\controllers
 */
class TransactionController extends WalletServiceController
{
    const REFRESH_OTP_SESSION_NAME = 'REFRESH_OTP_TRANSACTION_CODE';
    /**
     * @var array;
     */
    public $post;

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->post = Yii::$app->request->post();
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->post();
        $query = WalletTransaction::find();
        if (isset($params['filter'])) {
            $filter = json_decode($params['filter'], true);
            if (isset($filter['status']) && $filter['status'] != -1) {
                $query->andWhere(['status' => $filter['status']]);
            }
            if (isset($filter['extract'])) {
                if ($filter['extract']['key'] != '' && $filter['extract']['value'] != '') {
                    $query->andWhere([$filter['extract']['key'] => $filter['extract']['value']]);
                } else if ($filter['extract']['key'] === '' && $filter['extract']['value'] != '') {
                    $query->andWhere([
                        'or',
                        ['wallet_transaction_code' => $filter['extract']['value']],
                        ['order_number' => $filter['extract']['value']],
                        ['like', 'payment_transaction', $filter['extract']['value']],
                    ]);
                    //search like
                }
            }
            if (isset($filter['range']) && $filter['range']['key'] != '' && count($filter['range']['value']) > 1) {
                $from = new \DateTime($filter['range']['value'][0]);
                $from = $from->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d');
                $to = new \DateTime($filter['range']['value'][1]);
                $to = $to->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d');
                $query->andWhere(['>=', $filter['range']['key'], $from])->andWhere([
                    '<=', $filter['range']['key'], $to
                ]);
            }
            if (($transaction_code = ArrayHelper::getValue($filter,'transaction_code'))){
                $query->andWhere(['like' , 'wallet_transaction_code' , $transaction_code ]);
            }
            if (($order_code = ArrayHelper::getValue($filter,'order_code'))){
                $query->andWhere(['like' , 'order_number' , $order_code ]);
            }
            if (($transaction_type = ArrayHelper::getValue($filter,'transaction_type'))){
                $query->andWhere(['type' => strtoupper($transaction_type)]);
            }
            if (($from_date = ArrayHelper::getValue($filter,'from_date'))){
                $from_date = date('Y-m-d H:i:s',strtotime(str_replace('/','-',$from_date).' 00:00:00'));
                $query->andWhere(['>=' , 'create_at' , $from_date]);
            }
            if (($to_date = ArrayHelper::getValue($filter,'to_date'))){
                $to_date = date('Y-m-d H:i:s',strtotime(str_replace('/','-',$to_date).' 23:59:59'));
                $query->andWhere(['<=' , 'create_at' , $to_date]);
            }
            if (($transaction_status = ArrayHelper::getValue($filter,'transaction_status'))){
                $query->andWhere(['status' => $transaction_status]);
            }
        }
        if (isset($params['wallet_merchant_id'])) {
            $query->andWhere(['wallet_merchant_id' => $params['wallet_merchant_id']]);
        }
        if (isset($params['wallet_client_id'])) {
            $query->andWhere(['wallet_client_id' => $params['wallet_client_id']]);
        }
        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['wallet_client_id' => Yii::$app->user->id]);
        }
        $total = $query->count();
        if (isset($params['limit'])) {
            $query->limit($params['limit'])->offset($params['offset']);
        }
        $data = $query->orderBy('id DESC')->all();
        return $this->response(true, Yii::t('wallet','Get all data success'), $data, 200, $total);
    }

    public function actionGetWithdraw()
    {
        $params = Yii::$app->request->post();
        $query = WalletTransaction::find()->where(['type' => WalletTransaction::TYPE_WITH_DRAW]);
        if (isset($params['filter'])) {
            $filter = json_decode($params['filter'], true);
            if (isset($filter['status']) && $filter['status'] != -1) {
                $query->andWhere(['status' => $filter['status']]);
            }
            if (isset($filter['extract'])) {
                if ($filter['extract']['key'] != '' && $filter['extract']['value'] != '') {
                    switch ($filter['extract']['key']){
                        case 'transactionCode':
                            $query->andWhere([ 'wallet_transaction_code' => $filter['extract']['value']]);
                            break;
                        case 'clientId':
                            $query->andWhere(['wallet_client_id' => $filter['extract']['value']]);
                            break;
                        default:
                            break;
                    }
                }
            }
            if (isset($filter['range']) && $filter['range']['key'] != '' && count($filter['range']['value']) > 1) {
                $from =$filter['range']['value']['start'];
                $to = $filter['range']['value']['end'];
                $query->andWhere(['>=', $filter['range']['key'], $from])->andWhere([
                    '<=', $filter['range']['key'], $to
                ]);
            }
        }
        if (isset($params['wallet_merchant_id'])) {
            $query->andWhere(['wallet_merchant_id' => $params['wallet_merchant_id']]);
        }
        if (isset($params['wallet_client_id'])) {
            $query->andWhere(['wallet_client_id' => $params['wallet_client_id']]);
        }
//        if(!Yii::$app->user->isGuest){
//            $query->andWhere(['wallet_client_id' => Yii::$app->user->id]);
//        }
        $total = $query->count();
        if (isset($params['limit'])) {
            $query->limit($params['limit'])->offset($params['offset']);
        }
        $data = $query->orderBy('id DESC')->all();
        return $this->response(true, Yii::t('wallet','Get all data success'), $data, 200, $total);
    }

    public function actionUpdateTransactionWithdraw()
    {
        if (($code = ArrayHelper::getValue($this->post, 'transaction_code', false)) === false) {
            return $this->response(false, Yii::t('wallet', 'Missing parameter ${parameter}', [
                'parameter' => 'transaction_code'
            ]), null, ResponseCode::ERROR);
        }
        if (($status = ArrayHelper::getValue($this->post, 'status', false)) === false) {
            return $this->response(false,Yii::t('wallet','Missing parameter ${parameter}', [
                'parameter' => 'Status'
            ]), null, ResponseCode::ERROR);
        }
        $model = $this->findModel($code);
        $wallet = WalletClient::findOne($model->wallet_client_id);
        if($model->getCurrentWalletClient() === null){
            $model->setCurrentWalletClient($wallet);
        }
        if ($model->status === WalletTransaction::STATUS_PROCESSING || $model->fixedOtpCode !== null) {
            // Todo: nothing

            $change = new ChangeBalanceForm();
            $change->amount = $model->debit_amount ? $model->debit_amount : $model->credit_amount;
            $change->walletTransactionId = $model->id;
            $change->wallet_client = $wallet;
            if ($status == 'success') {
                $rs = $change->withDraw();
                $sttChange = WalletTransaction::STATUS_COMPLETE;
            } else {
                $rs = $change->unFreezeAdd($model);
                $sttChange = WalletTransaction::STATUS_CANCEL;
            }
            if ($rs['success']) {
                if (!$model->updateTransaction($sttChange)) {

                    return $this->response(false, Yii::t('wallet','Have error when update your transaction to complete. BUT Change Withdraw request success!'), null, ResponseCode::ERROR);
                }
                if($sttChange === WalletTransaction::STATUS_CANCEL){
                    $model->refresh();
                    $wallet->refresh();
                    $model->setCurrentWalletClient($wallet);
                }
                $manager = new \common\mail\MailManager();
                $manager->setType($sttChange === WalletTransaction::STATUS_COMPLETE
                    ? \common\mail\Template::TYPE_TRANSACTION_TYPE_WITHDRAW_SUCCESS
                    : \common\mail\Template::TYPE_TRANSACTION_TYPE_WITHDRAW_FAILED);
                $manager->setActiveModel($model);
                $manager->setReceiver($model);
                $manager->send();
                return $this->response(true, $rs['message'], ['status' => 'complete', 'time' => $model->complete_at], ResponseCode::SUCCESS);
            }
            return $this->response(false, $rs['message'], null, ResponseCode::ERROR);

        }
        return $this->response(false, Yii::t('wallet','Your otp do not verify, please verify otp send on email or phone'), null, ResponseCode::INVALID);
    }

    /**
     * get detail of a transaction
     *  'otpInfo' available when transaction type is not credit
     * @return mixed
     * @throws \Throwable
     */
    public function actionDetail()
    {
        if (($code = ArrayHelper::getValue($this->post, 'transaction_code', false)) === false) {
            return $this->response(false, Yii::t('wallet', 'Missing parameter ${parameter}', [
                'parameter' => 'transaction_code'
            ]), null, ResponseCode::ERROR);
        }
        $model = $this->findModel($code);
        $attributeNames = [
            'wallet_transaction_code',
            'type',
            'description',
            'description',
            'totalAmount',
            'credit_amount',
            'debit_amount',
            'request_content',
            'payment_method',
            'payment_provider_name',
            'payment_bank_code',
            'payment_transaction',
            'order_number',
            'create_at',
            'update_at',
            'complete_at',
            'cancel_at',
            'fail_at',
            'status',
            'verified_at',
            'verify_expired_at',
            'verify_receive_type'
        ];
        $data = ['isValid' => $model->isValid];

        $modelAttributes = $model->getAttributes($attributeNames);
        $modelAttributes['statusText'] = WalletTransaction::getStatusLabels($model->status);
        $data = ArrayHelper::merge($data, [
            'transactionInfo' => $modelAttributes,
            'walletInterview' => Yii::$app->user->getIdentity()->getPublicProfile()
        ]);
        if (
            $model->verify_code !== null &&
            (
                $model->status === WalletTransaction::STATUS_QUEUE ||
                (
                    $model->status === WalletTransaction::STATUS_CANCEL &&
                    $model->verify_count <= WalletTransaction::OTP_COUNT_LIMIT &&
                    $model->refresh_count <= WalletTransaction::ATTEMPT_REFRESH_LIMIT
                )
            )
        ) {
            $data = ArrayHelper::merge($data, [
                'otpInfo' => [
                    'verified' => $model->verified_at === null ? false : true,
                    'receive_type' => $model->verify_receive_type,
                    'receive_type_text' => WalletTransaction::getVerifyReceiveTypeLabels($model->verify_receive_type),
                    'send_to' => $model->getOtpSendTo($model->verify_receive_type),
                    'count' => (WalletTransaction::OTP_COUNT_LIMIT - $model->verify_count) > 0 ? WalletTransaction::OTP_COUNT_LIMIT - $model->verify_count : 0,
                    'expired_at' => Yii::$app->formatter->asRelativeTime($model->verify_expired_at),
                    'expired_timestamp' => $model->verify_expired_at,
                    'refresh_count' => (WalletTransaction::ATTEMPT_REFRESH_LIMIT - $model->refresh_count) > 0 ? WalletTransaction::ATTEMPT_REFRESH_LIMIT - $model->refresh_count : 0,
                    'refresh_expired_timestamp' => $model->refresh_expired_at,
                    'refresh_expired_at' => Yii::$app->formatter->asRelativeTime($model->refresh_expired_at),
                ]
            ]);
        }
        return $this->response(true, Yii::t('wallet', 'Get detail of transaction {transaction_code} success', [
            'transaction_code' => $code
        ]), $data, ResponseCode::SUCCESS);
    }

    /**
     * create a transaction
     * if action run with scenario 'default' meaning nothing requited.
     * @return array
     * @see \wallet\modules\v1\models\form\TransactionForm::makeTransaction()
     * @see \wallet\modules\v1\models\WalletTransaction::createWalletTransaction()
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $form = new TransactionForm();
        if ($form->load($this->post)) {
            $result = $form->makeTransaction();
            return $this->response(true, $result['message'], $result['data'], $result['code']);
        }
        $this->response(false, Yii::t('wallet','Transaction not found'), null, ResponseCode::NOT_FOUND);
    }

    /**
     * validate otp code
     * @return mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionVerifyOpt()
    {
        if (($code = ArrayHelper::getValue($this->post, 'transaction_code', false)) === false) {
            return $this->response(false, Yii::t('wallet','Missing parameter ${parameter}', [
                'parameter' => 'transaction_code'
            ]), null, ResponseCode::ERROR);
        }
        if (($opt = ArrayHelper::getValue($this->post, 'otp_code', false)) === false) {
            return $this->response(false, Yii::t('wallet','Missing parameter ${parameter}', [
                'parameter' => 'otp_code'
            ]), null, ResponseCode::ERROR);
        }
        $model = $this->findModel($code);

        $valid = $model->validateOtpCode($opt);
        $model->refresh();
        if ($valid['valid']) {
            $form = new ChangeBalanceForm();
            $form->amount = $model->totalAmount;
            $form->walletTransactionId = $model->id;

            if ($model->type === WalletTransaction::TYPE_WITH_DRAW) {
                $manager = new \common\mail\MailManager();
                $manager->setType(\common\mail\Template::TYPE_TRANSACTION_TYPE_WITHDRAW);
                $manager->setActiveModel($model);
                $manager->setReceiver(WalletTransaction::className());
                $manager->send();
                return $this->response($valid['valid'], $valid['message'], $valid['data'], ResponseCode::SUCCESS);
            } else {
                $data = $form->payment();
            }
            if ($data['success'] === false) {
                return $this->response(false, $data['message'], null, $data['code']);
            }
        }
        return $this->response($valid['valid'], $valid['message'], $valid['data'], ResponseCode::SUCCESS);

    }

    /**
     * Todo: refresh otp
     * @return mixed
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionRefreshOtp()
    {
        $session = Yii::$app->session;
        if (($code = ArrayHelper::getValue($this->post, 'transaction_code', false)) === false) {
            return $this->response(false, Yii::t('wallet','Missing parameter ${parameter}', [
                'parameter' => 'transaction_code'
            ]), null, ResponseCode::ERROR);
        }
        if (($receiveType = ArrayHelper::getValue($this->post, 'otp_receive_type', false)) === false) {
            return $this->response(false, Yii::t('wallet','Missing parameter ${parameter}', [
                'parameter' => 'otp_receive_type'
            ]), null, ResponseCode::ERROR);
        }

        $model = $this->findModel($code);

        $name = self::REFRESH_OTP_SESSION_NAME . $model->getWalletTransactionCode();
        //Yii::$app->getSession()->destroy();
        $expiredAt = Yii::$app->formatter->asTimestamp("now 180 seconds");
        if ($model->refresh_count !== null && $model->refresh_count < WalletTransaction::ATTEMPT_REFRESH_LIMIT) {

            $now = Yii::$app->formatter->asTimestamp('now');
            if ($model->refresh_expired_at && $model->refresh_expired_at > $now) {
                $lastTime = Yii::$app->formatter->asRelativeTime($model->refresh_expired_at);
                $message = 'OTP could not be send. Maybe you attempted to refresh otp over {lastCount} times , you should wait {lastTime} for the time of next refresh. Thank you.';
                $message = Yii::t('wallet', $message, [
                    'lastCount' => $model->refresh_count,
                    'lastTime' => $lastTime
                ]);
                return $this->response(false, $message, null, ResponseCode::INVALID);
            } else {
                $count = $model->refresh_count + 1;
                $model->updateAttributes([
                    'refresh_count' => $count,
                    'refresh_expired_at' => $expiredAt,
                ]);
            }
        } elseif ($model->refresh_count !== null && $model->refresh_count === WalletTransaction::ATTEMPT_REFRESH_LIMIT) {
            $message = 'Refreshed {attemptRefreshCount} times ,You can not refresh otp now, thank you';
            $message = Yii::t('wallet', $message, ['attemptRefreshCount' => WalletTransaction::ATTEMPT_REFRESH_LIMIT]);
            return $this->response(false, $message, null, ResponseCode::INVALID);
        } else {
            $count = $model->refresh_count + 1;
            $model->updateAttributes([
                'refresh_count' => $count,
                'refresh_expired_at' => $expiredAt,
            ]);
        }
        list($success, $message, $receives, $code) = $model->sendOtpCode($receiveType, true, false);
        if (!$model->isValid && $success) {
            $model->updateTransaction(WalletTransaction::STATUS_QUEUE);
            $form = new ChangeBalanceForm();
            $form->amount = $model->totalAmount;
            $form->walletTransactionId = $model->id;
            if (($rs = $form->freeze())['success'] === true) {
                $model->getRedis()->push([$model->getWalletTransactionCode(), Yii::$app->formatter->asDatetime('now'), 1]);
            } else {
                $model->updateTransaction(WalletTransaction::STATUS_FAIL);
                return $this->response(false, $rs['message'], null, ResponseCode::ERROR);
            }
        }
        $data = [
            'count' => $model->verify_count,
            'expired' => Yii::$app->formatter->asRelativeTime($model->verify_expired_at),
            'expired_timestamp' => $model->verify_expired_at
        ];
        if (count($receives) == 2 && array_key_exists('receive_type', $receives) && array_key_exists('send_to', $receives)) {
            $data = ArrayHelper::merge($data, $receives);
        }

        return $this->response($success, $message, $data, $code);

    }

    /**
     * transaction success. update transaction status to complete.
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionSuccess()
    {
        if (($code = ArrayHelper::getValue($this->post, 'transaction_code', false)) === false) {
            return $this->response(false, Yii::t('wallet','Missing parameter ${transaction_code}', [
                'parameter' => 'transaction_code'
            ]), null, ResponseCode::ERROR);
        }
        $model = $this->findModel($code);
        if ($model->status === WalletTransaction::STATUS_PROCESSING || $model->fixedOtpCode !== null) {
            // Todo: nothing
            if (!$model->updateTransaction(WalletTransaction::STATUS_COMPLETE)) {
                return $this->response(false, Yii::t('wallet','Have error when update your transaction to complete'), null, ResponseCode::ERROR);
            }
            $manager = new \common\mail\MailManager();
            $manager->setType(\common\mail\Template::TYPE_TRANSACTION_TYPE_PAY_ORDER_SUCCESS);
            $manager->setActiveModel($model);
            $manager->setReceiver(['mail' => $model->getOtpSendTo(WalletTransaction::VERIFY_RECEIVE_TYPE_EMAIL)]);
            $manager->setStore(1);
            $manager->send();
            return $this->response(true,Yii::t('wallet','Thank you for use wallet'), ['status' => 'complete', 'time' => $model->complete_at], ResponseCode::SUCCESS);
        }
        return $this->response(false, Yii::t('wallet','Your otp do not verify, please verify otp send on email or phone'), null, ResponseCode::INVALID);
    }

    public function actionTestUpdateTransaction($id)
    {
        $model = WalletTransaction::findOne($id);
        $model->payment_transaction = '567654323567654';
        $model->updateTransaction(2, true);
        var_dump($model->payment_transaction);
    }

    /**
     * @param $code
     * @return \wallet\modules\v1\models\WalletTransaction
     */
    public function findModel($code)
    {
        if (($model = WalletTransaction::find()->where(['wallet_transaction_code' => $code])->one()) != null) {
            return $model;
        }
        return $this->response(false, 'Not Found', null, ResponseCode::NOT_FOUND);
    }
}