<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/23/2018
 * Time: 4:43 PM
 */

namespace wallet\modules\v1\base\interfaces;

use wallet\modules\v1\models\form\ChangeBalanceForm;
use wallet\modules\v1\models\form\RefundRequestForm;
use wallet\modules\v1\models\form\TransactionForm;
use wallet\modules\v1\models\form\TopUpForm;
use wallet\modules\v1\models\form\WithDrawForm;

interface IWallet
{
    /**
     * Function nạp tiền vào ví
     * Step1: Tăng tiền ESC.WS
     * Step2: Tăng tiền ví owner
     * Step3: Notify
     * @param TopUpForm $form
     * @return mixed
     */
    public function topUpMoney($form);

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
    public function withDrawMoney($request);

    /**
     * Thực hiện giao dịch ví
     * Step1: Check balance
     * Step2: Giảm tiền ví owner
     * Step3: Giảm tiền ví ESC.WS
     * Step4: Notify
     * @param TransactionForm $transactionForm
     * @return mixed
     */
    public function makeTransaction($transactionForm);

    /**
     * Đóng băng giao dịch
     * @param ChangeBalanceForm $changeBalanceForm
     * @return mixed
     */
    public function freezeMoney($changeBalanceForm);

    /**
     * Giải phóng đóng băng giao dịch
     * @param ChangeBalanceForm $changeBalanceForm
     * @return mixed
     */
    public function unFreezeMoney($changeBalanceForm);

    /**
     * Lấy số dư ví
     * @return mixed
     */
    public function getBalance();

    /**
     * Thực hiện tăng tiền
     * Step1: Tăng tiền ví ESC.WS
     * Step2: Tăng tiền ví owner
     * @param ChangeBalanceForm $changeBalanceForm
     * @return mixed
     */
    public function doIncreaseBalance($changeBalanceForm);

    /**
     * Thực hiện trừ tiền
     * Step1: Trừ tiền ví owner
     * Step2: Trừ tiền ví ESC.WS
     * @param ChangeBalanceForm $changeBalanceForm
     * @return mixed
     */
    public function doDecreaseBalance($changeBalanceForm);

    /**
     * Đối soát số dư ví kiem tra so du kha dung
     * @param $amount:so tien can kiem tra
     * @return mixed
     */
    public function crossCheckBalance($amount);

    /**
     * Lấy lịch sử giao dịch /log transaction
     * @return mixed
     */
    public function getTransactionHistory();


    /**
     * Check thông tin giao dịch
     * @param $transId
     * @return mixed
     */
    public function checkTransaction($transId);

    /**
     * Tặng điểm thưởng vào bulk
     * @return mixed
     */
    public function giveBulkPoint();

    /**
     * Hoàn tiền vào ví
     * @param RefundRequestForm $form
     * @return mixed
     */
    public function refundToWallet($form);

    /**
     * Thông báo thành công
     * @return mixed
     */
    public function makeNotify($code);



}