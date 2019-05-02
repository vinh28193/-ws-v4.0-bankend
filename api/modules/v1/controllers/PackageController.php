<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 13:02
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\models\db\Package;
use common\models\DeliveryNote;
use common\helpers\ChatHelper;
use common\components\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

class PackageController extends BaseApiController
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
                'roles' => ['operation','master_operation']
            ],
        ];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        $query = DeliveryNote::find();
        $query->filterRelation();
        $query->defaultSelect();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'ps',
                'pageParam' => 'p',
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);

        $query->filter($requestParams);

        return $this->response(true, 'get data success', $dataProvider);
    }
    public function actionView($id)
    {
        if ($id) {
            $package = Package::find()->where(['order_id' => (int)$id])->with('shipment')->asArray()->all();
            return $this->response(true, 'success', $package);
        }
    }

    public function actionDelete($id)
    {
        $post = Yii::$app->request->post();
        $model = Package::findOne($id);
        if (!$model->delete()) {
            Yii::$app->wsLog->push('order', 'deletePackageItem', null, [
                'id' => $model->$post['ordercode'],
                'request' => $this->post,
            ]);
            return $this->response(false, 'delete package' . $id . 'error');
        }
        $dirtyAttributes = $model->getDirtyAttributes();
        $messages = "order {$model->$post['ordercode']} Delete Package {$this->resolveChatMessage($dirtyAttributes,$model)}";
        ChatHelper::push($messages, $model->$post['ordercode'], 'GROUP_WS', 'SYSTEM');
        Yii::$app->wsLog->push('order', 'deletePackage', null, [
            'id' => $model->$post['ordercode'],
            'request' => $this->post,
        ]);
        return $this->response(true, 'delete package' . $id . 'success');
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