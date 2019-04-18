<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\modelsMongo\RestApiCall;
use common\modelsMongo\ChatMongoWs;
use common\models\Order;
use Yii;
use common\data\ActiveDataProvider;
use common\helpers\ChatHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class RestApiChatController extends BaseApiController
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
            $_user_app = 'Weshop2019';
            /***Todo Set**/
            $_request_ip = Yii::$app->getRequest()->getUserIP();
            $isNew = isset($_post['isNew']) && $_post['isNew'] === 'yes';
            //code vandinh : status order
            $isSupporting = true;
            if ($_post['type_chat'] == 'GROUP_WS') {
                $jsonfile_vi = Yii::getAlias('@webroot/listchats/chat-vi.json');

                $listchats = $this->readFileChat($jsonfile_vi);
                $check_string_chat = $this->checkStringInFile($_post['message'], $listchats);
                $isSupporting = $check_string_chat;
            }
            //end code vandinh
            $_rest_data = ["ChatMongoWs" => [
                "success" => true,
                "message" => is_array($_post['message']) ? @json_encode($_post['message']) : $_post['message'],
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
                if ($isNew === true && $isSupporting == true) {

                    // code vandinh staus order is new or chat supporting

                    $messages = "order {$_post['Order_path']} Create Chat {$_post['type_chat']} ,{$_post['message']}, order new to supporting";
                    Order::updateAll([
                        'current_status' => Order::STATUS_SUPPORTING
                    ], ['ordercode' => $_post['Order_path']]);

                    //code update action log
                    if (!$model->save()) {
                        Yii::$app->wsLog->push('order', $model->getScenario(), null, [
                            'id' => $_post['Order_path'],
                            'request' => $this->post,
                            'response' => $model->getErrors()
                        ]);
                        return $this->response(false, $model->getFirstErrors());
                    }
                    ChatHelper::push($messages, $_post['Order_path'], 'GROUP_WS', 'SYSTEM');
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

    protected function writeFileChat($value, $jsonfile)
    {

        $tempArray = $this->readFileChat($jsonfile);
        $value = strtolower($value);
        if (empty($tempArray)) {
            $tempArray = array();
        }
        array_push($tempArray, $value);
        $jsonData = json_encode($tempArray);
        file_put_contents($jsonfile, $jsonData);

    }

    protected function readFileChat($jsonfile)
    {

        $string = file_get_contents($jsonfile);
        $listchats = json_decode($string, true);
        return $listchats;
    }

    protected function checkStringInFile($string, $file)
    {
        $return = false;
        if (in_array($string, $file)) $return = true;
        return $return;
    }
}
