<?php

namespace userbackend\controllers;

use Yii;
use common\models\Order;
use userbackend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $post = Yii::$app->request->get('statsu');
        $dataProvider = OrderSearch::find()
            ->with('products');
        if (isset($post)) {
            $dataProvider ->where(['current_status', $post]);
        }
        $models = $dataProvider->all();
//        var_dump($models);
//        die();
//        $countQuery = clone $dataProvider;
//        $pages = new Pagination(['totalCount' => $countQuery->count()]);
//        $models = $dataProvider->offset($pages->offset)
//            ->limit($pages->limit)
//            ->all();

        return $this->render('index', [
            'models' => $models,
//            'pages' => $pages
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view');
    }
}
