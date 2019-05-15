<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 4/23/2018
 * Time: 4:57 PM
 */

namespace wallet\modules\v1\models\form;


use wallet\modules\v1\models\WalletClient;
use common\models\db\Customer;
use yii\base\Model;

class CreateWalletForm extends Model
{
    public $customer_id;
    public $store_id;
    public $currency_id;
    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $isValidate;
    public $customer_ip;

    public function rules()
    {
        return [
            ['customer_id', 'required'],
            ['currency_id', 'required'],
            ['customer_name', 'required'],
            ['customer_email', 'required'],
            ['customer_phone', 'required'],
            ['customer_ip', 'required'],
            ['amount', 'required'],
            ['customer_name', 'trim'],
            ['customer_phone', 'trim'],
            ['customer_name', 'trim']
        ];
    }

    /**
     * @return WalletClient
     */
    public function createWallet()
    {
        $wl = new WalletClient();
        $customer = Customer::findOne($this->customer_id);
        $wl->customer_id = $this->customer_id;
        $wl->email = $this->customer_email;
        $wl->username = $wl->email;
        $wl->password_hash = $customer->password;
        $wl->auth_key = $customer->salt;
        $wl->status=WalletClient::STATUS_INACTIVE;
        $wl->save();
        $wl->sentOtp();
        return $wl;
    }
}