<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 6/7/2019
 * Time: 11:17 AM
 */

namespace api\modules\v1\controllers;
use api\controllers\BaseApiController;
use common\modelsMongo\ListNotification;
use Yii;

class ListNotificationController extends BaseApiController
{
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
            [
                'allow' => true,
                'actions' => ['update', 'delete', 'create'],
            ],
        ];
    }

    public function actionCreate() {
        $post = Yii::$app->request->post();
        $userId = Yii::$app->user->getId();
        $now = Yii::$app->getFormatter()->asTimestamp('now');
        $model = new ListNotification();
        $model->user_id = $userId;
        $model->title = $post['title'];
        $model->body = $post['body'];
        $model->click_action = $post['link'];
        $model->created_at = $now;
        if (!$model->save()) {
            return $this->response(false, 'error');
        }
        return $this->response(false, 'success');
    }

}