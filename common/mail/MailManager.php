<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-26
 * Time: 08:51
 */

namespace common\mail;

use Yii;
use yii\base\InvalidConfigException;

class MailManager extends \yii\base\Component
{

    /**
     * @var integer
     */
    private $_store;

    /**
     * setter
     * @param $store integer|\common\components\Store
     * @return $this
     */
    public function setStore($store){
        if(is_object($store) && $store instanceof \common\components\Store){
            $store =  $store->getStoreId();
        }
        $this->_store = $store;
        return $this;
    }
    public function getStore(){
        if(!$this->_store){
            return 1;
        }
        return $this->_store;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type){
        $validType = (new Template())->getTypeLabels();
        $validType = array_keys($validType);
        $this->_type = $type;
        return $this;

    }
    public function getType(){
        return $this->_type;
    }
    public $_type;

    /**
     * @var \yii\db\ActiveRecord
     */
    private $_activeModel;

    /**
     * @param $model
     * @return $this
     */
    public function setActiveModel($model){
        $this->_activeModel = $model;
        return $this;
    }

    public function getActiveModel(){
        return $this->_activeModel;
    }

    private $_additionText;
    public function setAdditionText($additionText){
        $this->_additionText = $additionText;
        return $this;
    }
    public function getAdditionText(){
        return $this->_additionText;
    }

    private $_partContent;
    public function setPartContent($partContent){
        $this->_partContent = $partContent;
        return $this;
    }
    public function getPartContent(){
        return $this->_partContent;
    }

    private $_targets;
    public function getTarget($name = null){
        return $this->_targets[$name] ? $this->_targets[$name] : null;
    }

    /**
     * @return BaseTarget[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getTargets(){
        $targets = $this->_targets;
        if($targets === null){
            $targets = $this->getDefaultTarget();
        }
        $this->_targets = [];
        foreach ($targets as $target){
            if(is_array($target) && isset($target['class'])){
                $registerTarget = Yii::createObject($target);
            }elseif ($target instanceof BaseTarget){
                $registerTarget = $target;
            }else{
                $registerTarget = \yii\di\Instance::ensure($target,BaseTarget::className());
            }
            if($registerTarget->isActive($this->getType())){
                $this->_targets[$registerTarget->id] = $registerTarget;
            }
        }
        return $this->_targets;
    }
    public function setTargets($targets){
        $this->_targets = $targets;
        return $this;
    }
    public function addTargets($targets){
        if (empty($this->_data)) {
            $this->_targets = $targets;
        } else {
            $this->_targets = array_merge($this->_targets, $targets);
        }
        return $this;
    }

    public function getDefaultTarget(){
        return [
            MailTarget::className(),
            PhoneTarget::className()
        ];
    }
    /**
     * @var ReceiverInterface | string
     */
    private $_receiver;

    public function getReceiver($target = null){
        $receiver = $this->_receiver;
        if ($target !== null && $target instanceof BaseTarget){
            if(is_string($receiver) && (($receiver = Yii::createObject($receiver)) instanceof ReceiverInterface)){
                /** @var $receiver ReceiverInterface*/
                return $receiver->extract($target);
            }elseif (is_object($receiver) && $receiver instanceof ReceiverInterface){
                return $receiver->extract($target);
            }elseif (is_array($receiver)){
                return isset($receiver[$target->getId()]) ? $receiver[$target->getId()] : null;
            }
        }

        return $receiver;
    }
    public function setReceiver($receiver){
        $this->_receiver = $receiver;
        return $this;
    }
    private $_sender;

    public function setSender($sender){
        $this->_sender = $sender;
        return $this;
    }
    public function getSender(){
        return $this->_sender;
    }

    private $_template;
    public function getTemplate(){
        if(!$this->_template){
            if($this->getType() !== null && $this->getStore() !== null){
                $query = Template::find();
                $query->where([
                    'AND',
                    ['type' => $this->getType()],
                    ['status' => Template::STATUS_ACTIVE],
                    ['store' => $this->getStore()]
                ]);
                /** @var $template Template */
                if (($template = $query->one()) !== null) {
                    if (($model = $this->getActiveModel()) !== null && is_object($model)) {
                        $template->setActiveModel($model) ;
                    }
                    if(($partContent = $this->getPartContent()) !== null){
                        $template->setPartContent($partContent);
                    }
                    if(($additionText = $this->getAdditionText()) !== null){
                        $template->setAdditionText($additionText);
                    }
                    if($template->to_address !== null){
                        $this->setReceiver(['mail' => $template->to_address]);
                    }
                    if(($sender = $this->getSender()) !== null && count($sender) ===2){
                        list ($address,$name) = $sender;
                        $template->from_address = $address;
                        $template->from_name = $name;
                    }
                    Yii::info($template->getHaystacks(),__METHOD__);
                    $this->setTemplate($template);
                }
            }
        }
        return $this->_template;
    }
    public function setTemplate($template){
        $this->_template = $template;
    }
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function send(){
        if(($template = $this->getTemplate()) !== null){
            $targets = $this->getTargets();
            $result = [];
            foreach ($targets as $id => $target){
                if(($receiver = $this->getReceiver($target)) !== null){
                    $result[$target->getId()] = $target->send($template,$receiver);
                }

            }
        }

    }

}