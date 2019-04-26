<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/25/2019
 * Time: 9:13 AM
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\modelsMongo\ListChat;
use Yii;

class ListChatMongoController extends BaseApiController
{

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true)
            ],
        ];
    }

    public function actionIndex() {
        $chat = ListChat::find()->asArray()->all();
        return $this->response(true, 'success', $chat);
    }
    public function actionCreate() {
        $post = Yii::$app->request->post();
        $now = Yii::$app->getFormatter()->asTimestamp('now');
        $chat = new ListChat();
        $chat->note = $post['noteC'];
        $chat->content = $post['contentC'];
        $chat->status = $post['statusC'];
        $chat->time_start = $now;
        if (!$chat->save()) {
            return $this->response(false, 'error', $chat->getErrors());
        }
        return $this->response(true, 'success', $chat);
    }
}