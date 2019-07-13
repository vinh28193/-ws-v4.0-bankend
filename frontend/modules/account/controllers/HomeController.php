<?php

namespace frontend\modules\account\controllers;

use Accouting\CreateMerchantByIdRequest;
use common\components\cart\CartManager;
use common\modelsMongo\ListNotification;
use frontend\modules\payment\providers\wallet\WalletService;
use Yii;
use common\models\Order;
use common\models\User;
use userbackend\models\HomeSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Accouting\AccoutClient as Client;
use Grpc;

// Accouting
use Accouting\Merchantinfo;
use Accouting\GetListMerchantByIdRequest;
use Accouting\GetListMerchantByIdResponse;
use Accouting\AccoutingClient;

// UserClient
use User\UserClient;
use User\SignUpRequest;


/**
 * HomeController implements the CRUD actions for Order model.
 */
class HomeController extends BaseAccountController
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
        /** @var User $user_info */
        $user_info = Yii::$app->user->getIdentity();
        $orders = Order::find()
            ->where(['=', 'customer_id', $user_info->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        $total = count($orders);
        $wallet = null;
        if($user_info->bm_wallet_id){
            $greeterClient = new Client('206.189.94.203:50054', [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]);
            $request = new GetListMerchantByIdRequest();
            $request->setUserId($user_info->bm_wallet_id);
            $request->setCountryCode($user_info->getCountryCode());
            list($reply, $status) = $greeterClient->GetListMerchantById($request)->wait();
            /** @var GetListMerchantByIdResponse $reply */
            $wallet = !$reply->getError() && count($reply->getData()) ? $reply->getData()[0] : null;
        }
        $totalCart = (new CartManager())->countItems();
        return $this->render('index', [
            'wallet' => $wallet,
            'orders' => $orders,
            'total' => $total,
            'totalCart' => $totalCart
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

//    public function actionWatchedNotification($code) {
//        $id = Yii::$app->user->getId();
//        $model = ListNotification::find()
//            ->where(['AND',
//                ['user_id' => $id],
//                ['_id' => $code]
//        ])->one();
//        $model->watched = 1;
//        $model->save();
//    }
    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend','The requested page does not exist.'));
    }
}
