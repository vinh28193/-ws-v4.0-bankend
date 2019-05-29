<?php

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\BaseApiController;
use common\components\KeyChatList;
use yii\helpers\ArrayHelper;

class RestApiChatlistsController extends BaseApiController
{

    public $filename;

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

    public function actionIndex()
    {
        return $this->response(true, 'Success', $this->keyChatManger->read());
    }

    public function actionCreate()
    {
        if (($content = ArrayHelper::getValue($this->post, 'content')) === null) {
            return $this->response(false, 'can not resolver request');
        }

        $dataChat = [
            'id' => @uniqid(),
            'content' => $content,
            'active' => 1,
            'date_set' => Yii::$app->formatter->asDateTime('now'),
        ];

        if (!$this->keyChatManger->write($dataChat)) {
            return $this->response(false, 'can not save chat key: ' . $content);
        }
        return $this->response(true, 'Success', $this->keyChatManger->read());

    }


    public function actionUpdate($id)
    {
        $post = (array)$this->get;
        $_id =  ArrayHelper::getValue($post,'id');
        $active = ArrayHelper::getValue($post,'content');

        Yii::info("PUT Param Update Active/ Inactive ");
        Yii::info([
            'id'=>$id,
            '_id' => $_id,
            'active'=> $active,
            'hasId' =>$this->keyChatManger->has($id),
            'hasActive' =>$this->keyChatManger->has($active)
        ], __CLASS__);

        if($this->keyChatManger->read()){
            foreach ($this->keyChatManger->read() as $key => $value){
                if ($value['id'] == $id ) {
                    $value['active'] = $active;
                    if($this->keyChatManger->remove($key)){
                        $this->keyChatManger->write($value);
                        Yii::info("Update Active/ Inactive sucess ");
                        //return $this->response(true, 'Success '.$id, $value);
                        return $this->response(true, 'Success', $this->keyChatManger->read());
                    } else {  return $this->response(false, 'can not remove chat key To Update: ' . $id); }
                }
            }
            return $this->response(true, 'Success', $this->keyChatManger->read());
        }
        return $this->response(false, 'Something wrong!', []);
    }



    public function actionDelete($id)
    {
        $post = (array)$this->get;
        $id = (integer)ArrayHelper::getValue($post,'index');
        $content = ArrayHelper::getValue($post,'content');

        Yii::info("Post Param DELETE ");
        Yii::info([
            'id' => $id,
            'content'=> $content
        ], __CLASS__);

        if (!$this->keyChatManger->remove($id)) {
            return $this->response(false, 'can not remove chat key: ' . $id);
        }
        return $this->response(true, 'Success', $this->keyChatManger->read());
    }

}
