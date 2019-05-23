<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 5/23/2019
 * Time: 1:59 PM
 */

namespace frontend\modules\account\models;

use common\helpers\ChatHelper;
use yii\base\Model;

class ChatFrom extends Model
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderCode', 'chatText'], 'safe'],
        ];
    }


    public $orderCode;
    public $chatText;


    public function attributes()
    {
        return ['orderCode', 'chatText'];
    }

    public function chat(){

        if(!$this->validate()){

            return false;
        }
        return ChatHelper::push($this->chatText,$this->orderCode, 'WS_CUSTOMER' , 'BACK_END');
    }
    private $_messages = [];

    public function getMessages()
    {
        if (empty($this->_messages)) {
            $this->_messages =  ChatHelper::pull($this->orderCode, 'WS_CUSTOMER' , 'BACK_END');
        }
        return $this->_messages;
    }
}