<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/28/2018
 * Time: 9:35 AM
 */

namespace wallet\modules\v1\models\traits;

use common\components\Language;
use DateInterval;
use DateTime;
use wallet\modules\v1\models\form\ChangeBalanceForm;
use wallet\modules\v1\models\form\CreateWalletForm;
use wallet\modules\v1\models\form\RefundRequestForm;
use wallet\modules\v1\models\form\TopUpForm;
use wallet\modules\v1\models\form\TransactionForm;
use wallet\modules\v1\models\form\WithDrawForm;
use wallet\modules\v1\models\WalletClient;

trait WalletClientTrait
{
    /**
     * Function nạp tiền vào ví
     * Step1: Tăng tiền ESC.WS
     * Step2: Tăng tiền ví owner
     * Step3: Notify
     * @param TopUpForm $form
     * @return mixed
     */
    public function topUpMoney($form)
    {
        return $form->topUp();
    }

    /**
     * Function rút tiền khỏi ví
     * Step1: Check balance
     * Step2: Giảm tiền ví owner + tính phí withDraw?
     * Step3: Giảm tiền ví ESC.WS
     * Step4: Chuyển tiền sang tài khoản chỉ định
     * Step5: Notify
     * @param WithDrawForm $request
     * @return mixed
     */
    public function withDrawMoney($request)
    {
        return $request->withDraw();
    }

    /**
     * Thực hiện giao dịch ví
     * Step1: Check balance
     * Step2: Giảm tiền ví owner
     * Step3: Giảm tiền ví ESC.WS
     * Step4: Notify
     * @param TransactionForm $transactionForm
     * @return bool|mixed
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function makeTransaction($transactionForm)
    {
        return $transactionForm->makeTransaction();
    }

    /**
     * Đóng băng giao dịch
     * @param ChangeBalanceForm $changeBalanceForm
     * @return mixed
     */
    public function freezeMoney($changeBalanceForm)
    {
        return $changeBalanceForm->freeze();
    }

    /**
     * Giải phóng đóng băng giao dịch (hủy giao dịch cộng lại tiền )
     * @param ChangeBalanceForm $changeBalanceForm
     * @return mixed
     */
    public function unFreezeMoney($changeBalanceForm)
    {
        return $changeBalanceForm->unFreezeAdd();
    }

    /**
     * Lấy số dư ví
     * @return mixed
     */
    public function getBalance()
    {
        // TODO: Implement getBalance() method.
        return $this->usable_balance;
    }


    /**
     * Thực hiện tăng tiền
     * Step1: Tăng tiền ví ESC.WS
     * Step2: Tăng tiền ví owner
     * @param ChangeBalanceForm $changeBalanceForm
     * @return mixed
     */
    public function doIncreaseBalance($changeBalanceForm)
    {
        // TODO: Implement doIncreaseBalance() method.
    }

    /**
     * Thực hiện trừ tiền
     * Step1: Trừ tiền ví owner
     * Step2: Trừ tiền ví ESC.WS
     * @param ChangeBalanceForm $changeBalanceForm
     * @return mixed
     */
    public function doDecreaseBalance($changeBalanceForm)
    {
        // TODO: Implement doDecreaseBalance() method.
    }

    /**
     * Đối soát số dư ví kiem tra so du kha dung
     * @param $amount :so tien can kiem tra
     * @return mixed
     */
    public function crossCheckBalance($amount)
    {
        return $amount <= $this->getBalance();
    }

    /**
     * Lấy lịch sử giao dịch /log transaction
     * @return mixed
     */
    public function getTransactionHistory()
    {
    }

    /**
     * Check thông tin giao dịch
     * @param $transId
     * @return mixed
     */
    public function checkTransaction($transId)
    {
        // TODO: Implement checkTransaction() method.
    }

    /**
     * Tặng điểm thưởng vào bulk
     * @return mixed
     */
    public function giveBulkPoint()
    {
        // TODO: Implement giveBulkPoint() method.
    }

    /**
     * Hoàn tiền vào ví
     * @param RefundRequestForm $form
     * @return mixed
     */
    public function refundToWallet($form)
    {
        return $form->refund();
    }

    /**
     * Thông báo thành công
     * @return mixed
     */
    public function makeNotify($code)
    {
        // TODO: Implement makeNotify() method.
    }

    /**
     * Tạo ví
     * @param CreateWalletForm $form
     * @return mixed
     */
    public function createWallet($form)
    {
        if ($form->validate())
            return $form->createWallet();
    }

    /**
     * Lấy thông tin ví
     * @return WalletClient
     */
    public function getWalletDetail()
    {
        return \Yii::$app->user->identity;
    }

    /**
     * Cập nhật thông tin ví
     * @return mixed
     */
    public function updateWalletInfo()
    {
        // TODO: Implement updateWalletInfo() method.
    }

    /**
     * Xóa tài khoản ví
     * @return mixed
     */
    public function deleteWallet()
    {
        $this->status = self::STATUS_INACTIVE;
    }

    /**
     * Chuyển tiền từ ví sang ví
     * @return mixed
     */
    public function transferWallet()
    {
        // TODO: Implement transferWallet() method.
    }

    /**
     * Tạo access key truy cập ví
     * @return mixed
     */
    public function createAccessKey()
    {
        // TODO: Implement createAccessKey() method.
    }

    public function createRequestKey()
    {
        // TODO: Implement createRequestKey() method.
    }

    public function createClient()
    {
        // TODO: Implement createClient() method.
    }


    /**
     * Tao ma xac thuc otp
     * @return boolean
     */
    public function sentOtp()
    {
        $this->otp_veryfy_code = rand(100000, 999999);
        $this->otp_veryfy_count = 0;
        $minutes_to_add = 5;
        $time = new DateTime(date('Y-m-d H:i:s'));
        $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $this->otp_veryfy_expired_at = $time->format('Y-m-d H:i:s');
        \SmsClient::sendSms($this->customer_phone, Language::t('otp-code', 'Mã xác thực OTP ví Weshop là ' . $this->otp_veryfy_code . '. Mã có giá trị trong vòng 5 phút.'));
        $this->save(false);
    }

    /**
     * @param $otp Mã otp
     * @return boolean
     */
    public function verifyOtp($otp)
    {
        return $this->otp_veryfy_code == $otp && $this->otp_veryfy_expired_at > date('Y-m-d H:i:s');
    }
}