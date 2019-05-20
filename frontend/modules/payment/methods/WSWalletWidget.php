<?php


namespace frontend\modules\payment\methods;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use frontend\modules\payment\providers\wallet\WalletService;

class WSWalletWidget extends MethodWidget
{


    public function init()
    {
        if ($this->getIsGuest() === false) {
            $this->getInfo();
        }
        $this->registerClientScripts();
    }

    public function run()
    {
        parent::run();
        return $this->render('ws_wallet', ['wallet' => $this->getInfo()]);
    }

    public function registerClientScripts()
    {
        $options = [
            'is_guest' => $this->getIsGuest(),
            'current_balance' => 0,
            'freeze_balance' => 0,
            'usable_balance' => 0,
            'otp_receive_type' => 0,
            'receive_types' => []
        ];
        if (($wallet = $this->getInfo()) !== null) {
            $options = ArrayHelper::merge($options, [
                'current_balance' => $wallet['current_balance'],
                'freeze_balance' => $wallet['freeze_balance'],
                'usable_balance' => $wallet['usable_balance'],
                'receive_types' => [
                    'phone' => $wallet['customer_phone'],
                    'email' => $wallet['email'],
                ]
            ]);
        }
        $options = Json::htmlEncode($options);
        $this->getView()->registerJs("ws.wallet.init($options);");
    }

    private $_info;

    protected function getInfo()
    {
        if ($this->_info === null && $this->getIsGuest() === false) {
            $client = new WalletService();
            if (($responseApi = $client->walletInformation())['success']) {
                $this->_info = $responseApi['data'];
            }
        }
        return $this->_info;
    }

    protected function getIsGuest()
    {
        return (new WalletService())->isGuest();
    }
}