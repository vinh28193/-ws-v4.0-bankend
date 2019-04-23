<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\components\KeyChatList;
use common\modelsMongo\RestApiCall;
use common\modelsMongo\ChatMongoWs;
use common\models\Order;
use Yii;
use common\data\ActiveDataProvider;
use common\helpers\ChatHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use api\modules\v1\controllers\service\ChatlistsServiceController;
use common\components\StoreManager;

class RestApiChatController extends BaseApiController
{
    /** Role :
        case 'cms':
        case 'warehouse':
        case 'operation':
        case 'sale':
        case 'master_sale':
        case 'master_operation':
        case 'superAdmin' :
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
                'roles' => $this->getAllRoles(true, ['user','cms', 'warehouse' ,'operation','master_sale','master_operation']),
            ],
        ];
    }
    /**
     * @var KeyChatList
     */
    public $keyChatManger;


    public function init()
    {
        parent::init();
        $this->keyChatManger = new KeyChatList();
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
            'group-viewed' => ['POST'],
            'customer-viewed' => ['POST']
        ];
    }

    public function actionIndex()
    {
        $response = ChatMongoWs::search($params = '');
        return $this->response(true, 'Success', $response);
    }

    public function actionCreate()
    {
        $_post = (array)$this->post;
        if (isset($_post) !== null) {
            @date_default_timezone_set('Asia/Ho_Chi_Minh');
            $model = new ChatMongoWs();
            //  $model->attributes = $_post;
            $_user_Identity = Yii::$app->user->getIdentity();
            $_user_id = $_user_Identity->getId();
            $_user_email = $_user_Identity['email'];
            $_user_AuthKey = $_user_Identity->getAuthKey();
            $_user_name = $_user_Identity['username'];
            //----ToDo Need More Infor param
            $_user_app = 'Weshop2019'; /***Todo Set**/
            $_request_ip = Yii::$app->getRequest()->getUserIP();
            // $isNew = isset($_post['isNew']) && $_post['isNew'] === 'yes';
            //code vandinh : status order
 
            $isSupported = 0; //1:true;0:false
            if($_post['type_chat'] == 'GROUP_WS')
            {
                $isSupported = $this->keyChatManger->has($_post['message']);

            }
            $order =  Order::find()->where(['ordercode' => $_post['Order_path']])->one();
            $current_status = $order->current_status;
            $isNew = false;
            if($current_status == Order::STATUS_NEW)
            {
                $isNew = true;
            }
            $isNew = $isNew ? true : false;
            $isSupported = $isSupported ? true : false;

            //end code vandinh
            $_rest_data = ["ChatMongoWs" => [
                "success" => true,
                "message" => is_array($_post['message']) ? @json_encode($_post['message']) : $_post['message'] ,
                "date" => Yii::$app->getFormatter()->asDatetime('now'),
                "user_id" => $_user_id,
                "user_email" => $_user_email,
                "user_name" => $_user_name,
                "user_app" => $_user_app,
                "user_request_suorce" => $_post['suorce'],  // "APP/FRONTEND/BACK_END"
                "request_ip" => $_request_ip, // Todo : set
                "user_avatars" => null,
                "Order_path" => $_post['Order_path'],
                "is_send_email_to_customer" => null,
                "type_chat" => $_post['type_chat'], // 'TYPE_CHAT : GROUP_WS/WS_CUSTOMER // Todo : set
                "is_customer_vew" => null,
                "is_employee_vew" => null
            ]];


            if ($model->load($_rest_data) and $model->save()) {
                $id = (string)$model->_id;
                // chat group ws from new to supported
               
                if($isNew === true &&  $isSupported === true)
                {
                        Order::updateAll([
                            'current_status' => Order::STATUS_SUPPORTED  
                        ],['ordercode' => $_post['Order_path']]);
                }
                //chat group customer from new to supporting
                if($isNew === true && $_post['type_chat'] == 'WS_CUSTOMER')
                {

                 // code vandinh staus order is new or chat supporting


                    $messages = "order {$_post['Order_path']} Create Chat {$_post['type_chat']} ,{$_post['message']}, order new to supporting";
                    // ToDo : @Phuc : Phải check xuống db lần nữa xem hiện tại trang thái Của Order là gì ?
                    // + Có thỏa mã điều kiện chuyển sang SUPPORTED không ?
                    // thi mới chuyển SUPPORTED

                    if ($isNew === true) {
                        Order::updateAll([
                            'current_status' => Order::STATUS_SUPPORTING,
                            'supporting' => Yii::$app->getFormatter()->asTimestamp('now') // ToDo Sai Cái này chỉ Supported khi nhân viên Chát
                        ],['ordercode' => $_post['Order_path']]);
                    } else {
                        Order::updateAll([
                            'current_status' => Order::STATUS_SUPPORTING
                        ],['ordercode' => $_post['Order_path']]);
                
                //code update action log
                    if (!$model->save())
                     {
                        Yii::$app->wsLog->push('order', $model->getScenario(), null, [
                            'id' => $_post['Order_path'],
                            'request' => $this->post,
                            'response' => $model->getErrors()
                        ]);
                        return $this->response(false, $model->getFirstErrors());
                     }
                    ChatHelper::push($messages, $_post['Order_path'], 'GROUP_WS' , 'SYSTEM');
                    Yii::$app->wsLog->push('order', $model->getScenario(), null, [
                    'id' => $_post['Order_path'],
                    'request' => $this->post,
                    'response' => $_post['message']
                ]);

                }

                return $this->response(true, 'Success', $response = $model->attributes);
            } else {
                Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
            }

        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    public function actionUpdate($id)
    {
        if ($id !== null) {
            $model = $this->findModel($id);
            $model->attributes = $this->post;
            /***Todo -  Validate data model ***/
            if ($model->save()) {
                Yii::$app->api->sendSuccessResponse($model->attributes);
            } else {
                Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    public function actionView($id)
    {
        if ($id !== null) {
            $response = ChatMongoWs::find()
                ->where(['Order_path' => $id])
                ->asArray()->all();
            return $this->response(true, "Get  $id success", $response);
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->can('canDelete', ['id' => $model->id]);
        $model->delete();
        Yii::$app->api->sendSuccessResponse($model->attributes);
    }

    public function actionAddlistchat()
    {
        return 1;
    }
    public function findModel($id)
    {
        if (($model = ChatMongoWs::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }


        /**
     * @param $dirtyAttributes
     * @param $reference \common\components\db\ActiveRecord
     * @return string
     */


    protected function resolveChatMessage($dirtyAttributes, $reference)
    {

        $results = [];
        foreach ($dirtyAttributes as $name => $value) {
            if (strpos($name, '_id') !== false && is_numeric($value)) {
                continue;
            }
            $results[] = "`{$reference->getAttributeLabel($name)}` changed from `{$reference->getOldAttribute($name)}` to `$value`";
        }

        return implode(", ", $results);
    }


}
