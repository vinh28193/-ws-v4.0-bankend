<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 17:29
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\models\Order;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class OrderController extends BaseApiController
{

    public function rules()
    {
        return array_merge(parent::rules(), [
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
                'roles' => $this->getAllRoles(true, 'user'),
                'permissions' => ['canCreate']
            ],
            [
                'allow' => true,
                'actions' => ['update', 'delete'],
                'roles' => $this->getAllRoles(true, 'user'),
            ],
        ]);
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['POST'],
            'view' => ['GET'],
            'delete' => ['DELETE']
        ];
    }

    /**
     *  `get given`
     *      ?q=pen&q_ref=all&tm_ref=create_time&tm=2019-01-01 00:00:00&ps=pain&page=1&per-pace=20
     * `post given`
     *  {
     *      "filter":{
                "keyword": {"key":"all","value":"pen"},
     *          "datetime": {"key":"create_at","value":["2019-01-01 00:00:00","2019-01-30 23:59:59"]},
     *          "paymentStatus":"pain",
     *          "type":"shop",
     *          ....
     *      },
     *      "page":"1",
     *      "per-page":"20"
     * }
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        $query = Order::find();
        $query->withFullRelations();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->filter($requestParams);

        return $this->response(true, 'ok', $dataProvider);
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionCreate()
    {
        $model = new Order();
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', \yii\helpers\Url::toRoute(['/order/view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->response(true, "Get order $id success", $this->findModel($id));
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id,false);
        $this->can('canUpdate', ['id' => $model->id]); // OWner is Update
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $this->response(true, "Get order $id success", $model);
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id,false);
        $this->can('canDelete', ['id' => $model->id]);
        $model->delete();
        return $this->response(true, "Delete order $id success", $model);
    }

    /**
     * @param $condition
     * @param bool $with
     * @return null|Order
     * @throws NotFoundHttpException
     */
    protected function findModel($condition, $with = true)
    {
        $query = Order::find();
        if ($with) {
            $query->withFullRelations();
        }
        if (is_numeric($condition)) {
            $condition = ['id' => $condition];
        }
        $query->where($condition);
        if (($model = $query->one()) === null) {
            throw new NotFoundHttpException("Not found order");
        }
        return $model;
    }

}