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
    public function actionIndex() {
        $userId = Yii::$app->user->getId();
        $model = ListNotification::find()->where(['user_id' => $userId])->asArray()->all();
        $total = count($model);
        $data = [
            'model' => $model,
            'total' => $total
        ];
        return $this->response(true, 'success', $data);
    }

    public function actionUpdate($code) {
        if ($code) {
            $model = ListNotification::find()->where(['_id' => $code])->one();
            $model->watched = 1;
            if (!$model->save()) {
                return $this->response(false, 'error');
            }
            return $this->response(true, 'success', $model);
        }
        return $this->response(false, 'can not $code');
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
        $model->watched = 0;
        if (!$model->save()) {
            return $this->response(false, 'error');
        }
        return $this->response(false, 'success');
    }

}