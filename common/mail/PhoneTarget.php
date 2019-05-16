<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-26
 * Time: 11:50
 */

namespace common\mail;

use yii\helpers\ArrayHelper;
use common\components\MessageClient;
use common\components\Store;

class PhoneTarget extends BaseTarget
{

    public $id = 'phone';

    public $provider;
    public $logProvider;
    public $from;
    public $content;

    /**
     * @param Template $template
     * @return mixed|void
     */
    public function prepare($template)
    {
        if ($template->store === Store::STORE_WESHOP_VN) {
            $this->provider = MessageClient::PROVIDER_VN;
            $this->logProvider = MessageClient::LOG_PROVIDER_WALLET_SMS;
        } else {
            $this->provider = MessageClient::PROVIDER_NEXMO;
            $this->logProvider = MessageClient::LOG_PROVIDER_WALLET_NEXMO;
            $this->from = 'Weshop Global';
            if ($template->store === Store::STORE_WESHOP_MY) {
                $this->from = 'WeshopMy';
            } elseif ($template->store === Store::STORE_WESHOP_ID) {
                $this->from = 'WeshopID';
            } elseif ($template->store === Store::STORE_WESHOP_PH) {
                $this->from = 'WeshopPH';
            } elseif ($template->store === Store::STORE_WESHOP_SG) {
                $this->from = 'WeshopSG';
            }
        }
        $this->content = $template->getTextContentReplace();
    }

    public function handle($receive)
    {
        (new MessageClient([
            'provider' => $this->provider,
            'logProvider' => $this->logProvider,
            'from' => $this->from,
            'to' => $receive,
            'content' => $this->content
        ]))->send();
    }

    public function isActive()
    {
        if(parent::isActive()){
            $notSend = [
                Template::TYPE_CUSTOMER_REGISTER_JWT,
                Template::TYPE_CUSTOMER_REGISTER_OTP,
                Template::TYPE_CUSTOMER_RESET_PASSWORD,
                Template::TYPE_CUSTOMER_REGISTER_SUCCESS,
                Template::TYPE_CUSTOMER_INVITE_REGISTER,
                Template::TYPE_PAY_ORDER,
                Template::TYPE_THANKS_FOR_PURCHASE,
                Template::TYPE_ORDER_REQUEST_APPROVED,
                Template::TYPE_ORDER_TRACKING_STEP_1,
                Template::TYPE_ORDER_TRACKING_STEP_2,
                Template::TYPE_ORDER_TRACKING_STEP_3,
                Template::TYPE_ORDER_TRACKING_STEP_4,
                Template::TYPE_ORDER_TRACKING_STEP_5,
                Template::TYPE_ADDITION_FEE_REQUESTED,
                Template::TYPE_ADDITION_FEE_COMPLETED,
                Template::TYPE_NOTIFICATION,
                Template::TYPE_WALLET_NOTIFY,
            ];
            return !ArrayHelper::isIn($this->getType(),$notSend);
        }
        return false;
    }
}