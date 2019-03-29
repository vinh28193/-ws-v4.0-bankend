<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:05
 */

namespace common\components\log\driver;

use common\components\log\LoggingDriverInterface;
use common\modelsMongo\ActionLogWS;
use Yii;

class MongoLog extends ActionLogWS implements LoggingDriverInterface
{

    /**
     * @var \common\models\User|\common\models\Customer
     */
    public $userIdentity;

    public function init()
    {
        parent::init();
        $this->userIdentity = Yii::$app->getUser()->getIdentity();
    }

    /**
     * @var string
     */
    public $type;

    public function getProvider()
    {
        return $this->type;
    }

    public function push($action, $message, $params = [])
    {
        $model = new self();
        foreach ($params as $name => $value) {
            if ($name === 'request' || $name === 'data_input') {
                $name = 'data_input';
                $value = $this->resolveRawValue($value);
            } else if ($name === 'response' || $name === 'data_output') {
                $name = 'data_input';
                $value = $this->resolveRawValue($value);
            }
            if ($model->hasAttribute($name)) {
                $model->$name = $value;
            } else {
                Yii::warning("can not set unknown property $name", __METHOD__);
            }
        }
//        $model->success = true;
        $userRole = $this->userIdentity ? Yii::$app->getAuthManager()->getRolesByUser($this->userIdentity->getId()) : null;

        if(!empty($userRole) && $userRole !== null){
            $userRole = array_keys($userRole);
            $userRole = reset($userRole);
            $model->Role = $userRole;
        }
        $model->action_path = $action;
        $model->LogType = $this->getProvider();
        $model->user_id = $this->userIdentity ? $this->userIdentity->getId() :null;
        $model->user_email = $this->userIdentity ? $this->userIdentity->email : null;
        $model->user_name = $this->userIdentity ? $this->userIdentity->username : null;
        $model->user_avatar = null;
        $model->user_app = 'weshop 2019';
        $model->request_ip = Yii::$app->getRequest()->getUserIP();
        $model->date = Yii::$app->getFormatter()->asDatetime('now');
        return $model->save(false);
    }

    public function pull($condition)
    {
        return self::find()->andWhere($condition)->andWhere([
            'LogType' => $this->getProvider()
        ])->all();
    }

    public function resolveRawValue($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        return $value;
    }
}