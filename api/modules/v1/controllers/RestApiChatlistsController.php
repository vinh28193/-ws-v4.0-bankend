<?php

namespace api\modules\v1\controllers;

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
        if (!$this->keyChatManger->write($content)) {
            return $this->response(false, 'can not save chat key: ' . $content);
        }
        return $this->response(true, 'Success', $this->keyChatManger->read());

    }


    public function actionDelete($content)
    {
        if (!$this->keyChatManger->remove($content)) {
            return $this->response(false, 'can not remove chat key: ' . $content);
        }
        return $this->response(true, 'Success', $this->keyChatManger->read());

    }

}
