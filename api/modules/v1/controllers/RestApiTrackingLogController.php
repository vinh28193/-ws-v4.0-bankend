<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\TrackingLogWs;
use yii\helpers\ArrayHelper;
use common\models\Manifest;
use common\models\TrackingCode;
use Yii;

class RestApiTrackingLogController extends BaseApiController
{
    /** Role :
     * case 'cms':
     * case 'warehouse':
     * case 'operation':
     * case 'sale':
     * case 'master_sale':
     * case 'master_operation':
     * case 'superAdmin' :
     **/
    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update'],
                'roles' => $this->getAllRoles(true),

            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canCreate']
            ],
            [
                'allow' => true,
                'actions' => ['update', 'delete'],
                'roles' => $this->getAllRoles(true, ['user', 'cms', 'warehouse', 'operation', 'master_sale', 'master_operation']),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE']
        ];
    }

    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $query = TrackingLogWs::find();
        if (isset($get['ordercode'])) {
            $query->andWhere(['id' => $get['ordercode']]);
        }
        if (isset($get['content'])) {
            $query->andWhere(['like', 'data_input', $get['content']]);
        }
        if (isset($get['ip'])) {
            $query->andWhere(['request_ip' => $get['ip']]);
        }
        if (isset($get['user_name'])) {
            $query->andWhere(['user_name' => $get['user_name']]);
        }
        if ((isset($get['startTime']) && isset($get['endTime']))) {
            $query->andWhere(['between', 'created_at', (int)Yii::$app->formatter->asTimestamp($get['startTime']), (int)Yii::$app->formatter->asTimestamp($get['endTime'])]);
        }
        if ($get['valueCreate'] == 0) {
            $model = $query->orderBy(['created_at' => SORT_DESC]);
        }
        if ($get['valueCreate'] == 1) {
            $model = $query->orderBy(['created_at' => SORT_ASC]);
        }
        $total = $model->count();
        $limit = isset($get['limit']) ? $get['limit'] : 10;
        $page = isset($get['page']) ? $get['page'] : 1;
        $offset = ($page - 1) * $limit;
        $model->limit($limit)->offset($offset);
        $query = $model->asArray()->all();
        $data = [
            'model' => $query,
            'totalCount' => $total
        ];
        return $this->response(true, "success", $data);
    }

    public function actionSubscribe()
    {

    	 $_post = (array)$this->post;
    	 $_user_Identity = Yii::$app->user->getIdentity();
         $time_warehosue = Yii::$app->formatter->asDateTime('now');
         if(isset($_post['time_warehouse']))
         {
         	$time_warehosue = $_post['time_warehouse'];
            $time_warehosue = Yii::$app->getFormatter()->asTimestamp($time_warehosue);
         }
         $manifest = $_post['manifest'];
         $_request_ip = Yii::$app->getRequest()->getUserIP();
         $boxme_warehosue = $_post['boxme'];
         $text     = $_post['text'];
         $store = $_post['store'];
         $log_type = $_post['log_type'];
         $user     = $_user_Identity['username'].' - '.$_user_Identity->getId();
         // $list_tracking_code = $_post['trackingcode'];
         // $list_tracking_code = explode(",",$list_tracking_code);
          $note = new \stdClass; 
          $note->store = $store;
          $note->boxme = $boxme_warehosue;
          $note->manifest = $manifest;
          $note->time_warehosue = $time_warehosue;  
          $note->text = $text;       
          if((int)$this->findModel($_post['trackingcode']) != 1)
         	     {    
	                 $manifestobj = Manifest::createSafe($manifest, 1,1, 1);
	                 // print_r($manifestobj);die();
	                 $object = new \stdClass; 
	                 $object->tracking_code = $_post['trackingcode'];
	                 $object->store_id = $manifestobj->store_id;
	                 $object->manifest_code = $manifestobj->manifest_code;
	                 $object->manifest_id = $manifestobj->id;
	                 
			         $_rest_data = ["TrackingLogWs" => [
			            'tracking' => $_post['trackingcode'],
			            'log_type' => $log_type,
			            'note'  => $note,
			            'user'   => $user,
			            'request_ip'=> $_request_ip,
			            'object' => $object

			            // Infor Field Notification
			        
			        ]];	

			         $model = new TrackingLogWs();
			         $model->load($_rest_data);
			         $model->save();
			        
		        }
         
          return $this->response(true, 'Success','Write log success');


         // $manifest = Manifest::createSafe($manifest, 1, 1);
         
   


    }

    public function actionUpdate($id)
    {
      
    }

    public function actionView($id)
    {
        if ($id !== null) {
            $response = TrackingLogWs::find()
                ->where(['_id' => $id])
                ->all();
            return $this->response(true, "Get  $id success", $response);
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    public function actionDelete($id)
    {
      
    }

    public function findModel($trackingcode)
    {

    	$result = false;
        $model = TrackingLogWs::find()
                ->where(['tracking' => $trackingcode])
                ->one();
         if (!empty($model)) 
         {     
         	$result = true;
         }   
        
         return $result;
    }
}
