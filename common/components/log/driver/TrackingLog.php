<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:05
 */

namespace common\components\log\driver;

use common\components\log\LoggingDriverInterface;
use common\modelsMongo\TrackingLogWs;
use common\models\Manifest;
use Yii;

class TrackingLog extends TrackingLogWs implements LoggingDriverInterface
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

    public function getProvided()
    {
       return $this->type;
    }

    public function pushData($action, $message, $params = [])
    {
         $model = new self();
    	 $_user_Identity = Yii::$app->user->getIdentity();
         $time_warehosue = Yii::$app->formatter->asDateTime('now');
         if(isset($params['time_warehouse']))
         {
         	$time_warehosue = $params['time_warehouse'];
            $time_warehosue = Yii::$app->getFormatter()->asTimestamp($time_warehosue);
         }
         $manifest = $params['manifest'];
         $_request_ip = Yii::$app->getRequest()->getUserIP();
         $boxme_warehosue = $params['boxme'];
         $text     = $params['text'];
         $store = $params['store'];
         $log_type = $params['log_type'];
         $user     = $_user_Identity['username'].' - '.$_user_Identity->getId();

          $note = new \stdClass; 
          $note->store = $store;
          $note->boxme = $boxme_warehosue;
          $note->manifest = $manifest;
          $note->time_warehosue = $time_warehosue;  
          $note->text = $text;    

          $query = TrackingLogWs::find()
                ->where(['tracking' => $trackingcode])
                ->one();

 	     if(empty($query))
 	     {    

	         $manifestobj = Manifest::createSafe($manifest, 1, 1);
	         $object = new \stdClass; 
	         $object->tracking_code = $params['trackingcode'];
	         $object->store_id = $manifestobj->store_id;
	         $object->manifest_code = $manifestobj->manifest_code;
	         $object->manifest_id = $manifestobj->id;

	         $_rest_data = ["TrackingLogWs" => [
	            'tracking' => $params['trackingcode'],
	            'log_type' => $log_type,
	            'note'  => $note,
	            'user'   => $user,
	            'request_ip'=> $_request_ip,
	            'object' => $object
	        
	        ]];	

	         $model = new TrackingLogWs();
	         if($model->load($_rest_data) && $model->save())
	         {
	         	 return $this->response(true, 'Success', $model); 
	         }
		         
	         
	      }
    }

    public function pullData($condition)
    {
        return self::find()->andWhere($condition)->andWhere([
            'LogType' => $this->type
        ])->all();
    }

    public function resolveRawValue($value)
    {
        if (is_array($value)) {
            $value = @json_encode($value);
        }
        return $value;
    }
}
