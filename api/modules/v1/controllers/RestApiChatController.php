<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\components\KeyChatList;
use common\modelsMongo\ListChat;
use common\modelsMongo\RestApiCall;
use common\modelsMongo\ChatMongoWs;
use common\models\Order;
use userbackend\cart\ShoppingCart;
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
            $_user_Identity = Yii::$app->user->getIdentity();
            $_user_id = $_user_Identity->getId();
            $_user_email = $_user_Identity['email'];
            $_user_AuthKey = $_user_Identity->getAuthKey();
            $_user_name = $_user_Identity['username'];
            //----ToDo Need More Infor param
            $_user_app = 'Weshop2019'; /***Todo Set**/
            $_request_ip = Yii::$app->getRequest()->getUserIP();
            $isNew = false; $isSupported = false; // 1:true;0:false
            $isSupporting = false;
            if ($_post['_chat'] == 'ORDER') {
                $order =  Order::find()->where(['ordercode' => $_post['Order_path']])->one();
                if($order == null){
                    Yii::$app->api->sendFailedResponse("Not found order by ordercode : .".$_post['Order_path']);
                }
                $current_status = $order->current_status;
                $paid_status = $order->total_paid_amount_local > 0 ? true : false ;
                if($current_status == Order::STATUS_NEW or $current_status == Order::STATUS_SUPPORTING)
                {
                    $isNew = true;
                }
            }
            if ($_post['_chat'] == 'CART') {
                if (isset($_post['isNew'])) {
                    if($_post['isNew'] == 'YES')
                    {
                        $isNew = true;
                    }
                }
            }
//            if ($_post['type_chat'] == 'GROUP_WS' && strlen(strstr($_post['message'], 'Supported')) > 0) {
//                $this->keyChatManger = new KeyChatList();
//                $mess = str_replace('-Type: Supported','',$_post['message']);
//                $listChat = $this->keyChatManger->read();
//                foreach ($listChat as $value) {
//                    if ($value['content'] == $mess) {
//                        $isSupported = true;
//                        break;
//                    }
//                }
//            }
            if (($_post['type_chat'] == 'GROUP_WS') && strlen(strstr($_post['message'], 'supporting')) > 0) {
                $mess = str_replace('-Type: supporting','',$_post['message']);
                $listChat = ListChat::find()->where(['status' => (string)(1)])->all();
                foreach ($listChat as $value) {
                    if ($value['content'] == $mess) {
                        $isSupporting = true;
                        break;
                    }
                }
            }
            $isNew = $isNew ? true : false;
            $isSupported = $isSupported ? true : false;

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
                if ($_post['_chat'] == 'ORDER') {
                    if($isNew === true &&  $isSupporting === true)
                    {
//                        Order::updateAll([
//                            'current_status' => Order::STATUS_CONTACTING,
//                            'contacting' => Yii::$app->getFormatter()->asTimestamp('now'),
//                        ],['ordercode' => $_post['Order_path']]);
                        $order = Order::find()->where(['ordercode' => $_post['Order_path']])->one();
                        $order->current_status = Order::STATUS_CONTACTING;
                        $order->contacting = Yii::$app->getFormatter()->asTimestamp('now');
                        $order->save();
                    }
                }

                if ($_post['_chat'] == 'CART') {
                    if($isNew === true &&  $isSupported === true)
                    {
                        $this->getCart()->updateSafeItem(($_post['type']), $_post['id'], $_post);
                    }
                    if($isNew === true &&  $isSupporting === true)
                    {
                        $this->getCart()->updateSafeItem(($_post['type']), $_post['id'], $_post);
                    }
                }
                $dirtyAttributes = $order->getDirtyAttributes();
                $messages = "<span class='text-danger font-weight-bold'>Order {$_post['Order_path']}</span> <br> - Create Chat <br> {$this->resolveChatMessage($dirtyAttributes, $order)}";
                ChatHelper::push($messages, $_post['Order_path'], 'GROUP_WS' , 'SYSTEM', null);
                Yii::$app->wsLog->push('order', $model->getScenario(), null, [
                    'id' => $_post['Order_path'],
                    'request' => $this->post,
                    'response' => $_post['message']
                ]);
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
            $results[] = "<span class='font-weight-bold'>- {$reference->getAttributeLabel($name)} :</span> <br> Changed from `{$reference->getOldAttribute($name)}` to `$value`";
        }

        return implode('<br> ', $results);
    }

    protected function getCart()
    {
        return Yii::$app->cart;
    }


}
