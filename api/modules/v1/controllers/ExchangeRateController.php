<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 6/27/2019
 * Time: 8:16 PM
 */

namespace api\modules\v1\controllers;


use common\fixtures\SystemExchangeRateFixture;
use common\models\db\SystemExchangeRate;
use api\controllers\BaseApiController;
use common\modelsMongo\ExchangeRateLog;
use Yii;

class ExchangeRateController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','view','delete','update','create'],
                'roles' => ['superAdmin']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex() {
        $model = SystemExchangeRate::find()->asArray()->all();
        return $this->response(true, 'success', $model);
    }

    public function actionUpdate($id) {
        $user = Yii::$app->user->identity;
        $post = Yii::$app->request->post();
        $model = SystemExchangeRate::findOne($id);
        $model->from = $post['from'];
        $model->to = $post['to'];
        $model->rate = $post['rate'];
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "Update Exchange Rate ID = {$id} :  {$this->resolveChatMessage($dirtyAttributes,$model)}";
        if (!$model->save()){
            return $this->response(false, 'error');
        }
        $log = new ExchangeRateLog();
        $log->ex_id = $id;
        $log->content = $messages;
        $log->updated_at = Yii::$app->getFormatter()->asTimestamp('now');
        $log->employee = $post['employee'];
        $log->username = $user->username;
        $log->ip = Yii::$app->getRequest()->getUserIP();
        $log->save();
        return $this->response(true, 'success');
    }

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