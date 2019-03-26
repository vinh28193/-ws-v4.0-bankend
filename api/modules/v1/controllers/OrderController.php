<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 17:29
 * Chặn Authen theo role được gán chưa viết  test @Phuchc
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\models\Order;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/***Cache PageCache **/
use yii\caching\DbDependency;
use yii\caching\TagDependency;

class OrderController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['pageCache'] = [
//            'class' => 'yii\filters\PageCache',
//            'only' => ['index'],
//            'duration' => 24 * 3600 * 365, // 1 year
//            'dependency' => [
//                'class' => 'yii\caching\ChainedDependency',
//                'dependencies' => [
//                    new DbDependency(['sql' => 'SELECT MAX(id) FROM ' . Order::tableName()])
//                ]
//            ],
//        ];
        return $behaviors;
    }


    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update', 'edit-image', 'edit-variant'],
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
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
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
     * "keyword": {"key":"all","value":"pen"},
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
        $params = Yii::$app->request->get();
        $response = Order::search($params);
        return $this->response(true, 'Success', $response);

    }

    /**
     * @throws ServerErrorHttpException
     */
    public function actionCreate()
    {
        if (isset($this->post) !== null) {
            // $this->can('canCreate',[]); // Supper is canCreate
            $model = new Order;
            $model->attributes = $this->post;
            if ($model->save()) {
                Yii::$app->api->sendSuccessResponse($model->attributes);
            } elseif ($model->save() === false) {
                Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
            }
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if ($id !== null) {
            $request = Order::find()
                ->where(['id' => $id])
                ->withFullRelations()
                ->asArray()->all();
            return $this->response(true, "Get order $id success", $request);
        } else {
            Yii::$app->api->sendFailedResponse("Invalid Record requested");
        }
    }

    /**
     * Todo
     *  common action update
     *      post given
     *          $_POST
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, false);
        Yii::info($this->post,__METHOD__);
        return $this->response(true,'ok',$this->post);
//
//        $this->can('canUpdate', $model);
//        $model->loadScenario($this->post);
//        $model->load($this->post,'');
//        if(!$model->save()){
//            Yii::$app->api->sendFailedResponse("Invalid Record requested", (array)$model->errors);
//        }
//        Yii::$app->api->sendSuccessResponse($model->attributes);
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
        $model = $this->findModel($id, false);
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
//        if ($with === true) {
//            $query->withFullRelations();
//        }
        if (is_numeric($condition)) {
            $condition = [$query->getColumnName('id') => $condition];
        }
        $query->where($condition);
        if (($model = $query->one()) === null) {
            throw new NotFoundHttpException("Not found order");
        }
        return $model;
    }


    public function actionEditImage($id)
    {
        $post = Yii::$app->request->post('image');
        var_dump($post);
        die();
        return $this->response(true, "Delete $id success", Yii::$app->request->get());
    }

    public  function actionEditVariant()
    {
        $post = Yii::$app->request->post();
        var_dump($post);
        die();
    }
}
