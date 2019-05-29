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
