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
        $get = Yii::$app->request->get();
        $chat = ListChat::find();
        if (isset($get['noteL'])) {
            $chat->where(['note', $get['noteL']]);
        }
        if (isset($get['contentL'])) {
            $chat->where(['content', $get['contentL']]);
        }
        return $this->response(true, 'success',  $chat->asArray()->all());
    }
    public function actionCreate() {
        $model = ListChat::find()->orderBy('code DESC')->limit(1)->one();
        $post = Yii::$app->request->post();
        $now = Yii::$app->getFormatter()->asTimestamp('now');
        $chat = new ListChat();
        $chat->note = $post['noteC'];
        $chat->code = $model->code + 1;
        $chat->content = $post['contentC'];
        $chat->status = $post['statusC'];
        $chat->time_start = $now;
        if (!$chat->save()) {
            return $this->response(false, 'error', $chat->getErrors());
        }
        return $this->response(true, 'success', $chat);
    }
    public function actionDelete($id) {
        $chat = ListChat::findOne(['code' => (int)$id]);
        if (!$chat->delete()) {
            return $this->response(false, 'error', $chat->getErrors());
        }
        return $this->response(true, 'success', $chat);
    }
}