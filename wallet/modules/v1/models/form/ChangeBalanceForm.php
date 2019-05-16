<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/9/2018
 * Time: 8:35 AM
 */

namespace wallet\modules\v1\models\form;


use common\components\ReponseData;
use common\models\db\Order;
use common\models\db\OrderPayment;
use common\models\db\WalletClient;
use common\models\payment\vietnam\NganLuong;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\WalletLog;
use wallet\modules\v1\models\WalletMerchant;
use wallet\modules\v1\models\WalletTransaction;
use yii\base\Model;
use yii\db\Exception;

class ChangeBalanceForm extends Model
{

    const TYPE_ADD = 1;
    const TYPE_REDUCE = 2;

    const WSVN_ESCROW = 'WSVN-ESCROW';
    const WSVN_COST = 'WSVN-COST';
    const WSVN_REVENUE = 'WSVN-REVENUE';

    public $amount; // số tiền cần thay đổi
    public $walletTransactionId; // id wallet_transaction
    /**
     * @var bool | \wallet\modules\v1\models\WalletClient
     */
    public $wallet_client = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'walletTransactionId'], 'required'],
            [['amount', 'walletTransactionId'], 'number', 'min' => 0]
        ];
    }

    protected function getWallet($sql = false, $id = 0)
    {
        if ($sql && $id) {
            $this->wallet_client = WalletClient::findOne($id);
            return $this->wallet_client;
        }
        if ($this->wallet_client) {
            return $this->wallet_client;
        }
        return \Yii::$app->user->identity;
    }

    protected function checkWalletLog($walletId, $typeWallet, $typeTransaction,$tranid = null)
    {
        $walletLog = WalletLog::find()->where(['typeWallet' => $typeWallet,
            'walletTransactionId' => $tranid ? $tranid : $this->walletTransactionId,
            'walletId' => $walletId,
            'status' => 1,
            'TypeTransaction' => $typeTransaction
        ])->one();
        if ($walletLog) {
            return true;
        }
        return false;
    }

    protected function getTransaction()
    {
        return WalletTransaction::find()->where(['id'=>$this->walletTransactionId])->one();
    }

    /**
     *
     *Tang tien nap tien tai khoan
     *
     * @return array|bool
     * @throws Exception
     */
    public function topUp()
    {
        if (!$this->checkValidate()) {
            $tran = WalletTransaction::find()->one();
            $log_topup = new WalletLog();
            $log_topup->walletTransactionId = $tran ? $tran->id : null;
            $log_topup->TypeTransaction = "LOG_TOPUP";
            $log_topup->walletId = $this->wallet_client ? $this->wallet_client->id : 1;
            $log_topup->typeWallet = WalletLog::TYPE_WALLET_CLIENT;
            $log_topup->description = ' Validate false. amount hoặc walletTransactionId sai. tran: '.$this->walletTransactionId;
            $log_topup->amount = 1;
            $log_topup->createDate = date('Y-m-d H:i:s');
            $log_topup->status = 2;
            $log_topup->save(0);
            return ReponseData::reponseArray(false, 'Validate false. amount hoặc walletTransactionId sai', []);
        }
        $transaction = $this->getTransaction();
        if (!$transaction || $transaction->type != WalletTransaction::TYPE_TOP_UP) {
            $log_topup = new WalletLog();
            $log_topup->walletTransactionId = $this->walletTransactionId ? $this->walletTransactionId : 1;
            $log_topup->TypeTransaction = "LOG_TOPUP";
            $log_topup->walletId = $this->wallet_client ? $this->wallet_client->id : 1;
            $log_topup->typeWallet = WalletLog::TYPE_WALLET_CLIENT;
            $log_topup->description = 'Không có transaction id :' . $log_topup->walletTransactionId. ' Cho phương thức TOP_UP';
            $log_topup->amount = 1;
            $log_topup->createDate = date('Y-m-d H:i:s');
            $log_topup->status = 2;
            $log_topup->save(0);
            return ReponseData::reponseArray(false, 'Không có transaction id :' . $this->walletTransactionId . ' Cho phương thức TOP_UP', []);
        }
        //CHeck ngan luong
        $checkpayment = new NganLuong();
        $checkpayment->token = $transaction->payment_transaction;
        $checkpayment->wallet_merchant_id = $transaction->wallet_merchant_id;
        $rs = $checkpayment->checkPayment(false);
        $rs = json_decode($rs, true);
        if ($rs['success']) {
            if (round($rs['data']['response_content']['total_amount']) != round($this->amount) || $rs['data']['response_content']['transaction_status'] != '00') {
                $mes = 'Đối chiếu transaction với Ngân lượng sai.';
                $log_topup = new WalletLog();
                $log_topup->walletTransactionId = $transaction->id;
                $log_topup->TypeTransaction = "LOG_TOPUP";
                $log_topup->walletId = $transaction->wallet_client_id;
                $log_topup->typeWallet = WalletLog::TYPE_WALLET_CLIENT;
                $log_topup->description = $mes;
                $log_topup->amount = $transaction->amount;
                $log_topup->createDate = date('Y-m-d H:i:s');
                $log_topup->status = 2;
                $log_topup->save(0);
                return ReponseData::reponseArray(false,$mes , []);
            }
        } else {
            $mes = 'Không có token :' . $checkpayment->token . ' trên Ngân Lượng';
            $log_topup = new WalletLog();
            $log_topup->walletTransactionId = $transaction->id;
            $log_topup->TypeTransaction = "LOG_TOPUP";
            $log_topup->walletId = $transaction->wallet_client_id;
            $log_topup->typeWallet = WalletLog::TYPE_WALLET_CLIENT;
            $log_topup->description = $mes;
            $log_topup->amount = $transaction->amount;
            $log_topup->createDate = date('Y-m-d H:i:s');
            $log_topup->status = 2;
            $log_topup->save(0);

            return ReponseData::reponseArray(false, $mes, []);
        }
        $wallet = $this->getWallet();
        if ($this->checkWalletLog($wallet->id, WalletLog::TYPE_WALLET_CLIENT, WalletLog::TYPE_TRANSACTION_TOPUP)) {
            return ReponseData::reponseArray(false, 'Đã có log ghi nạp tiền thành công cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, []);
        }
        if ($this->amount > 0) {
            $connection = \Yii::$app->db;
            $transaction_cnn = $connection->beginTransaction();
            $befor_amount = $wallet->current_balance;
            $check = (strpos('-' . $rs['data']['response_content']['bank_code'], 'MASTER') || strpos('-' . $rs['data']['response_content']['bank_code'], 'VISA'));
            try {
                $connection->createCommand()->update(
                    'wallet_client',
                    [
                        'current_balance' => $wallet->current_balance + $this->amount,
                        'usable_balance' => $wallet->usable_balance + $this->amount,
                        'withdrawable_balance' => $check ? $wallet->withdrawable_balance : $wallet->withdrawable_balance + $this->amount,
                        'total_topup_amount' => $wallet->total_topup_amount + $this->amount,
                        'last_topup_amount' => $this->amount,
                        'last_topup_at' => date('Y-m-d H:i:s')
                    ],
                    "id = " . $wallet->id
                )->execute();
                $wallet = $this->getWallet(true, $wallet->id);
                $decription = 'Nạp tiền thành công qua cổng thanh toán (+ ' . $this->amount . ' )';
                $this->saveWalletLog($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_TOPUP, $decription, 1);

                //Thay đổi số dư ESC
                $res = $this->changeMerchant(self::TYPE_ADD);
                if (!$res['success']) {
                    $transaction_cnn->rollBack();
                    return $res;
                }
                $transaction_cnn->commit();
                return ReponseData::reponseArray(true, 'Nạp tiền thành công qua cổng thanh toán (+ ' . $this->amount . ' )', $wallet->toArray());
            } catch (Exception $e) {
                $transaction_cnn->rollBack();
                $decription = 'Nạp tiền thất bại qua cổng thanh toán ( ' . $this->amount . ' )';
                $this->saveWalletLogNoConn($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_TOPUP, $decription, 2);
                return ReponseData::reponseArray(false, $decription, []);
            }
        }
        return ReponseData::reponseArray(false, 'Số tiền - amount <= 0', []);
    }

    /**
     * Tang  tien tai khoan do dc refund
     * @return array|bool
     * @throws Exception
     */
    public function refunded()
    {
        if (!$this->checkValidate()) {
            return ReponseData::reponseArray(false, 'Validate false. amount hoặc walletTransactionId sai', [],ResponseCode::INVALID);
        }
        $wallet = $this->getWallet();

        $transaction = $this->getTransaction();
        if (!$transaction || $transaction->type != WalletTransaction::TYPE_REFUND) {
            return ReponseData::reponseArray(false, 'Không có transaction id :' . $this->walletTransactionId . ' Cho phương thức REFUND', [],ResponseCode::REFUND_MERCHAIN_ID_NULL);
        }

        if ($this->checkWalletLog($wallet->id, WalletLog::TYPE_WALLET_CLIENT, WalletLog::TYPE_TRANSACTION_REFUN)) {
            return ReponseData::reponseArray(false, 'Đã có log ghi nạp tiền refurn thành công cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, [],ResponseCode::REFUND_TRANSACTION_DUPLICATE);
        }
        if ($this->amount > 0) {
            $connection = \Yii::$app->db;
            $befor_amount = $wallet->current_balance;
            $order = Order::find()->where(['binCode' => $transaction->order_number, 'remove' => 0])->one();
            $order_payment = OrderPayment::find()->where(['order_id' => $order->id,'status' => 'SUCCESS'])->all();
            if (!$order || !count($order_payment)) {
                return ReponseData::reponseArray(false, 'Không có order nào có bincode là: ' . $transaction->order_number, [],ResponseCode::ERROR);
            }
            $amount_paid = 0;
            foreach ($order_payment as $payment){
                if($payment->payment_type == 'FIRST_PAYMENT' || $payment->payment_type == 'ADDFEE'){
                    $amount_paid += $payment->total_paid_amount;
                }else{
                    $amount_paid -= $payment->total_paid_amount;
                }
            }
            if ($amount_paid < $this->amount) {
                return ReponseData::reponseArray(false, 'Tổng số tiền khách thanh toán cho bin (đã trừ các refund): ' . $transaction->order_number . ' là: '.$amount_paid.' (thấp hơn ' . $this->amount.')', [],ResponseCode::REFUND_TOTAL_AMOUNT_MAX);
            }

            $check = (strpos('-' . $order->BankName, 'MASTER') || strpos('-' . $order->BankName, 'VISA'));
            $transaction_cnn = $connection->beginTransaction();
            try {
                $connection->createCommand()->update(
                    'wallet_client',
                    [
                        'current_balance' => $wallet->current_balance + $this->amount,
                        'usable_balance' => $wallet->usable_balance + $this->amount,
                        'withdrawable_balance' => $check ? $wallet->withdrawable_balance : $wallet->withdrawable_balance + $this->amount,
                        'total_topup_amount' => $wallet->total_topup_amount + $this->amount,
                        'last_topup_amount' => $this->amount,
                        'last_topup_at' => date('Y-m-d H:i:s')
                    ],
                    "id = " . $wallet->id
                )->execute();
                $wallet = $this->getWallet(true, $wallet->id);
                $decription = 'Nạp tiền thành công qua refund từ bin-' . $transaction->order_number . ' (+ ' . $this->amount . ' )';
                $this->saveWalletLog($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_REFUN, $decription, 1);

                //Thay đổi số dư ESC
                $res = $this->changeMerchant(self::TYPE_ADD);
                if (!$res['success']) {
                    $transaction_cnn->rollBack();
                    return $res;
                }
                $transaction->updateTransaction(WalletTransaction::STATUS_COMPLETE,false);
                $transaction_cnn->commit();
                return ReponseData::reponseArray(true, $decription, $wallet->toArray(),ResponseCode::SUCCESS);
            } catch (Exception $e) {
                $transaction_cnn->rollBack();
                $decription = 'Nạp tiền thát bại qua refund từ bin-' . $transaction->order_number . ' ( ' . $this->amount . ' )';
                $this->saveWalletLogNoConn($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_REFUN, $decription, 2);
                return ReponseData::reponseArray(false, $decription, [],ResponseCode::NOT_FOUND);
            }
        }
        return ReponseData::reponseArray(false, 'Số tiền - amount <= 0', [],ResponseCode::REFUND_TOTAL_AMOUNT_MIN);
    }

    /**
     *Giam tien do khach rut
     * step 1: gọi đến hàm đóng băng số tiền cần rút
     * step 2: xác thực otp
     * step 3:
     *          3. (true) : gọi tới hàm rút tiền ( withDraw() ) để giải phóng đóng băng + thực tiện trừ tiền
     *          3. (false) : gọi đến hàm giải phóng đóng băng trả tiền cho khách;
     * @return array
     * @throws Exception
     */
    public function withDraw()
    {
        if (!$this->checkValidate()) {
            return ReponseData::reponseArray(false, 'Validate false. amount hoặc walletTransactionId sai', []);
        }
        $wallet = $this->getWallet();

        if ($this->checkWalletLog($wallet->id, WalletLog::TYPE_WALLET_CLIENT, WalletLog::TYPE_TRANSACTION_WITHDRAW)) {
            return ReponseData::reponseArray(false, 'Đã có log ghi rút tiền thành công cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, []);
        }
        $befor_amount = $wallet->current_balance;
        $conn = \Yii::$app->db;
        $trans = $conn->beginTransaction();
        try {
            $conn->createCommand()->update(
                'wallet_client',
                [
                    'freeze_balance' => $wallet->freeze_balance - $this->amount,
                    'current_balance' => $wallet->current_balance - $this->amount,
                    'total_withdraw_amount' => $wallet->total_withdraw_amount + $this->amount,
                    'last_withdraw_amount' => $this->amount,
                    'last_withdraw_at' => date('Y-m-d H:i:s'),
                ],
                "id = " . $wallet->id
            )->execute();
            $wallet = $this->getWallet(true, $wallet->id);
            $decription = 'Rút tiền thành công (- ' . $this->amount . ' )';
            $this->saveWalletLog($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_WITHDRAW, $decription, 1);
            $res = $this->changeMerchant(self::TYPE_REDUCE);
            if (!$res['success']) {
                $trans->rollBack();
                return $res;
            }
            $trans->commit();
            return ReponseData::reponseArray(true, $decription, $this->getWallet(true, $wallet->id)->toArray());
        } catch (Exception $exception) {
            $trans->rollBack();
            $decription = 'Rút tiền thất bại ( ' . $this->amount . ' )';
            $this->saveWalletLog($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_WITHDRAW, $decription, 2);
            return ReponseData::reponseArray(false, $decription, []);
        }

    }

    /**
     * @param bool $withdraw
     * @return array
     */
    public function freeze($withdraw = false)
    {
        if (!$this->checkValidate()) {
            return ReponseData::reponseArray(false, 'Validate false. amount hoặc walletTransactionId sai', []);
        }
        $wallet = $this->getWallet();
        $trans = $this->getTransaction();
        $ListTrans = WalletTransaction::find()->where(['wallet_client_id' => $wallet->id,
            'status'=> WalletTransaction::STATUS_QUEUE,
            'order_number'=>$trans->order_number,
            'type'=> WalletTransaction::TYPE_PAY_ORDER
        ])->all();

        $str = "";
        if($ListTrans){
            foreach ($ListTrans as $tran){
                if($tran->id != $this->walletTransactionId){
                    $unfee = $this->unFreezeAdd($tran);
                    $str .= ' . '.$unfee['message'];
                    if($unfee['success']){
                        $tran->updateTransaction(3,false);
                    }
                }
            }
        }
        if ($this->checkWalletLog($wallet->id, WalletLog::TYPE_WALLET_CLIENT, WalletLog::TYPE_TRANSACTION_FREEZE) && $trans->status != 0) {
            return ReponseData::reponseArray(false, 'Đang có giao dịch Đóng băng tiền cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, []);
        }
        if ($this->amount > 0 && $this->amount <= $wallet->usable_balance) {
            if ($this->amount > $wallet->withdrawable_balance && $withdraw) {
                return ReponseData::reponseArray(false, 'Số tiền có thể rút : ' . $wallet->withdrawable_balance, []);
            }
            $before_amount = $wallet->current_balance;
            $wallet->usable_balance = $wallet->usable_balance - $this->amount;
            $withdraw_before = $wallet->withdrawable_balance;
            $wallet->withdrawable_balance = ($wallet->withdrawable_balance - $this->amount) < 0 ? 0 : $wallet->withdrawable_balance - $this->amount;
            $wallet->freeze_balance = $wallet->freeze_balance + $this->amount;
            $wallet->save(0);
            $decription = json_encode([
                'type' => $withdraw ? 'freeze - WithDraw' : 'Freeze',
                'wallet' => $wallet->id,
                'listTran' => count($ListTrans),
                'list' => $str,
                'amount' => $this->amount,
                'withdraw' => $withdraw_before - $wallet->withdrawable_balance
            ]);
            $this->saveWalletLogNoConn($before_amount, $wallet, WalletLog::TYPE_TRANSACTION_FREEZE, $decription, 1);

            return ReponseData::reponseArray(true, $decription, $wallet->toArray());
        }
        return ReponseData::reponseArray(false, 'SỐ tiền amount <= 0 hoặc nhỏ hơn tổng số tiền có thể sử dụng trong ví', []);
    }

    /**
     * GIải phóng đóng băng cho trường hợp check otp false
     * bỏ đóng băng cộng lại tiền cho khách
     * @param null $tran
     * @return array
     */
    public function unFreezeAdd($tran = null)
    {
        $wallet = $this->getWallet();
        $tranid = $this->walletTransactionId;
        $amount = $this->amount;
        if($tran && $tran->debit_amount){
            $tranid = $tran->id;
            $amount = $tran->debit_amount;
        }else{
            $tran = $this->getTransaction();
        }
//        print_r($tran->status);die;
        $befor_amount = $wallet->current_balance;
        if((($this->checkWalletLog($wallet->id, WalletLog::TYPE_WALLET_CLIENT, WalletLog::TYPE_TRANSACTION_UNFREEZEADD,$tranid)
        || $this->checkWalletLog($wallet->id, WalletLog::TYPE_WALLET_CLIENT, WalletLog::TYPE_TRANSACTION_PAYMENT,$tranid))
        && $tran->status != 0)
        )
        {
            return ReponseData::reponseArray(false, 'Đã có log ghi bỏ đóng băng hoặc Payment cho transaction này : ' . $tranid);
        }
        $walletLog = WalletLog::find()->where(['typeWallet' => WalletLog::TYPE_WALLET_CLIENT,
            'walletTransactionId' => $tranid,
            'walletId' => $wallet->id,
            'status' => 1,
            'TypeTransaction' => WalletLog::TYPE_TRANSACTION_FREEZE
        ])->one();
        if (!$walletLog) {
            return ReponseData::reponseArray(false, 'không có log ghi đóng băng tiền thành công cho transaction này : ' . $tranid);
        }
        $data = json_decode($walletLog->description, true);
        if (!isset($data['withdraw'])) {
            return ReponseData::reponseArray(false, 'Lỗi dữ liệu trả về từ description freeze : ' . $walletLog->description);
        }
        if($wallet->freeze_balance < $amount ){
            return ReponseData::reponseArray(false, 'Tổng tiền đã đóng băng nhỏ hơn số tiền cần giải phóng');
        }
        $wallet->freeze_balance = $wallet->freeze_balance - $amount;
        $wallet->usable_balance = $wallet->usable_balance + $amount;
        $wallet->withdrawable_balance = $wallet->withdrawable_balance + $data['withdraw'];
        if ($wallet->save(0)) {
            $decription = 'Bỏ đóng băng cộng - trả ' . $amount . ' về tài khoản '.$wallet->id;
            $this->saveWalletLogNoConn($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_UNFREEZEADD, $decription, 1,false,$amount,$tranid);
            $tran->updateTransaction(3,false);
            return ReponseData::reponseArray(true, $decription, $wallet->toArray());
        }
        return ReponseData::reponseArray(false, 'Lỗi dữ liệu ');

    }

    /**
     * *Giam tien do khach thanh toán đơn hàng
     * step 1: gọi đến hàm đóng băng số tiền cần thanh toán
     * step 2: xác thực otp
     * step 3:
     *          3. (true) : gọi tới hàm payment()để giải phóng đóng băng + thực tiện trừ tiền
     *          3. (false) : gọi đến hàm giải phóng đóng băng trả tiền cho khách; -- unFreezeAdd()
     *
     * @return array
     * @throws Exception
     */
    public function payment()
    {
        if (!$this->checkValidate()) {
            return ReponseData::reponseArray(false, 'Validate false. amount hoặc walletTransactionId sai', []);
        }
        $wallet = $this->getWallet();
        $transaction = $this->getTransaction();
        if (!$transaction || $transaction->type != WalletTransaction::TYPE_PAY_ORDER) {
            return ReponseData::reponseArray(false, 'Không có transaction id :' . $this->walletTransactionId . ' Cho phương thức PAY_ORDER', []);
        }
        if ($this->checkWalletLog($wallet->id, WalletLog::TYPE_WALLET_CLIENT, WalletLog::TYPE_TRANSACTION_PAYMENT)) {
            return ReponseData::reponseArray(false, 'Đã có log ghi thanh toán thành công cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, []);
        }
        if($this->amount != $transaction->debit_amount){
            return ReponseData::reponseArray(false, 'Tham số amount truyền sang sai so với debit_amount trong transaction', []);
        }
        $befor_amount = $wallet->current_balance;
        $conn = \Yii::$app->db;
        $trans = $conn->beginTransaction();
        try {
            $conn->createCommand()->update(
                'wallet_client',
                [
                    'freeze_balance' => $wallet->freeze_balance - $this->amount,
                    'current_balance' => $wallet->current_balance - $this->amount,
                    'total_using_amount' => $wallet->total_using_amount + $this->amount,
                    'last_using_amount' => $this->amount,
                    'last_using_at' => date('Y-m-d H:i:s'),
                ],
                "id = " . $wallet->id
            )->execute();
            $wallet = $this->getWallet(true, $wallet->id);
            $decription = 'Thanh toán thành công - trừ ' . $this->amount . ' về tài khoản';
            $this->saveWalletLog($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_PAYMENT, $decription, 1);
            $res = $this->changeMerchant(self::TYPE_REDUCE);
            if (!$res['success']) {
                $trans->rollBack();
                return $res;
            }
            $trans->commit();
            return ReponseData::reponseArray(true, $decription, $this->getWallet(true, $wallet->id)->toArray());
        } catch (Exception $exception) {
            $trans->rollBack();
            $decription = 'Thanh toán thất bại - trả ' . $this->amount . ' về tài khoản';
            $this->saveWalletLog($befor_amount, $wallet, WalletLog::TYPE_TRANSACTION_PAYMENT, $decription, 2);

            return ReponseData::reponseArray(false, $decription, []);
        }
    }


    /**
     *  Thay đổi thông số tài khoản merchant
     * @param $type
     * @return array|bool
     * @throws Exception
     */
    protected function changeMerchant($type)
    {
        $wallet_esc = $this->getESCROW();
        $wallet = $this->getWallet();
        if ($type === self::TYPE_ADD) {
            $wallet_esc = $this->getESCROW();
            $wallet = $this->getWallet();
            if ($this->checkWalletLog($wallet_esc->id, WalletLog::TYPE_WALLET_MERCHANT, WalletLog::TYPE_TRANSACTION_MERCHANTADD)) {

                return ReponseData::reponseArray(false, 'Đã có log ghi tăng tiền ESC thành công cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, []);
            }
            $befor_amount = $wallet_esc->current_balance;
            $connection = \Yii::$app->db;
            $connection->createCommand()->update(
                'wallet_merchant',
                [
                    'current_balance' => $wallet_esc->current_balance + $this->amount,
                    'total_credit_amount' => $wallet_esc->total_credit_amount + $this->amount,
                    'last_amount' => $this->amount,
                    'last_updated' => date('Y-m-d H:i:s'),
                ],
                "id = " . $wallet_esc->id
            )->execute();
            $wallet_esc = $this->getESCROW();
            $decription = 'Giao dịch tăng thành công từ WalletClient ' . $wallet->id . ' - cộng ' . $this->amount . ' về tài khoản ESC';
            $this->saveWalletLog($befor_amount, $wallet_esc, WalletLog::TYPE_TRANSACTION_MERCHANTADD, $decription, 1, true);
            return ReponseData::reponseArray(true, 'tăng tiền ESC thành công cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, []);

        }
        if ($type === self::TYPE_REDUCE) {

            if ($this->checkWalletLog($wallet_esc->id, WalletLog::TYPE_WALLET_MERCHANT, WalletLog::TYPE_TRANSACTION_MERCHANTREDUCE)) {
                return ReponseData::reponseArray(false, 'Đã có log ghi giảm tiền ESC thành công cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, []);
            }
            $befor_amount = $wallet_esc->current_balance;
            $connection = \Yii::$app->db;
            $connection->createCommand()->update(
                'wallet_merchant',
                [
                    'current_balance' => $wallet_esc->current_balance - $this->amount,
                    'total_debit_amount' => $wallet_esc->total_debit_amount + $this->amount,
                    'last_amount' => $this->amount,
                    'last_updated' => date('Y-m-d H:i:s'),
                ],
                "id = " . $wallet_esc->id
            )->execute();
            $wallet_esc = $this->getESCROW();
            $decription = 'Giao dịch thanh toán (withdraw - payment) thành công từ WalletClient ' . $wallet->id . ' - giảm ' . $this->amount . ' về tài khoản ESC';
            $this->saveWalletLog($befor_amount, $wallet_esc, WalletLog::TYPE_TRANSACTION_MERCHANTREDUCE, $decription, 1, true);
            return ReponseData::reponseArray(true, 'giảm tiền ESC thành công cho transaction: ' . $this->walletTransactionId . '  - Wallet ' . WalletLog::TYPE_WALLET_CLIENT . ': ' . $wallet->id, []);
        }
        return false;
    }

    protected function getESCROW()
    {
        $tran = $this->getTransaction();
        return WalletMerchant::find()->where(['id' => $tran->wallet_merchant_id])->one();
    }

    /**
     * @param $BeforeAccumulatedBalances
     * @param $wallet
     * @param $typeTransaction
     * @param $description
     * @param $status
     * @param bool $merchant
     * @param int $amount_temp
     * @throws Exception
     */
    protected function saveWalletLog($BeforeAccumulatedBalances, $wallet, $typeTransaction, $description, $status, $merchant = false, $amount_temp = 0)
    {
        $conn = \Yii::$app->db;
        $conn->createCommand()->insert(
            'wallet_log',
            [
                'walletTransactionId' => $this->walletTransactionId,
                'TypeTransaction' => $typeTransaction,
                'walletId' => $wallet->id,
                'typeWallet' => $merchant ? WalletLog::TYPE_WALLET_MERCHANT : WalletLog::TYPE_WALLET_CLIENT,
                'description' => $description,
                'amount' => $amount_temp ? $amount_temp : $this->amount,
                'BeforeAccumulatedBalances' => $BeforeAccumulatedBalances,
                'AfterAccumulatedBalances' => $wallet->current_balance,
                'createDate' => date('Y-m-d H:i:s'),
                'storeId' => $wallet->store_id,
                'status' => $status,
            ]
        )->execute();
    }

    /**
     * @param $BeforeAccumulatedBalances
     * @param $wallet
     * @param $typeTransaction
     * @param $description
     * @param $status
     * @param bool $merchant
     * @param int $amount_temp
     * @return bool
     */
    protected function saveWalletLogNoConn($BeforeAccumulatedBalances, $wallet, $typeTransaction, $description, $status, $merchant = false, $amount_temp = 0,$tranId = null)
    {
        if (!$this->checkValidate()) {
            return false;
//            return 'Validate false. amount hoặc walletTransactionId sai';
        }
        $wallet_log = new WalletLog();
        $wallet_log->walletTransactionId = $tranId ? $tranId : $this->walletTransactionId;
        $wallet_log->TypeTransaction = $typeTransaction;
        $wallet_log->walletId = $wallet->id;
        $wallet_log->typeWallet = $merchant ? WalletLog::TYPE_WALLET_MERCHANT : WalletLog::TYPE_WALLET_CLIENT;
        $wallet_log->description = $description;
        $wallet_log->amount = $amount_temp ? $amount_temp : $this->amount;
        $wallet_log->BeforeAccumulatedBalances = $BeforeAccumulatedBalances;
        $wallet_log->AfterAccumulatedBalances = $wallet->current_balance;
        $wallet_log->createDate = date('Y-m-d H:i:s');
        $wallet_log->storeId = $wallet->store_id;
        $wallet_log->status = $status;
        return $wallet_log->save();
    }

    protected function checkValidate()
    {
        if (is_numeric($this->amount) && is_numeric($this->walletTransactionId) && $this->amount > 0 && $this->walletTransactionId > 0) {
            $transaction = WalletTransaction::findOne($this->walletTransactionId);
            if ($transaction) {
                return true;
            }
        }
        return false;
    }


}