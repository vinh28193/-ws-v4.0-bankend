<?php

namespace userbackend\controllers;

use Yii;
use common\models\Order;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HomeController implements the CRUD actions for Order model.
 */
class HomeController extends Controller
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
        $orders = Order::find()
//            ->where(['=', 'customer_id', 50])
        ->all();
        $total = count($orders);
        return $this->render('index', [
            'orders' => $orders,
            'total' => $total
        ]);
    }
}
