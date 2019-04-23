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
//        $userId = Yii::$app->user->getIdentity()->scope;
        $post = Yii::$app->request->get('status');
        $dataProvider = OrderSearch::find()
            ->with('products');
//            ->andWhere(['=', 'customer_id', $userId]);
        if (isset($post) && !empty($post)) {
            $dataProvider ->andWhere(['=','current_status', $post]);
        }
        $models = $dataProvider->all();

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
