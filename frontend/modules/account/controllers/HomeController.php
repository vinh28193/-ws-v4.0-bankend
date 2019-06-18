<?php

namespace frontend\modules\account\controllers;

use Accouting\CreateMerchantByIdRequest;
use common\components\cart\CartManager;
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


    public function actionGrpc()
    {
        /*
        $greeterClient = new  GreeterClient('206.189.94.203:50054', [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);

        $WaletBoxme  = new Merchantinfo();
        $WaletBoxme->setUserId(23);
        $WaletBoxme->setCountryCode('VN');
        $getBalanceCod = $WaletBoxme->getBalanceCod();
        var_dump($getBalanceCod);

        die("upiuiupp");



        print_r($greeterClient);
        $request = new GetListMerchantByIdRequest();die;
        $request->setUserId(23);
        $request->setCountryCode('VN');

        list($reply, $status) = $greeterClient->GetListMerchantById($request)->wait();


        print_r($reply);
        print_r($status);
        die("8989898");

        Yii::$app->response->data = [
            'status' => Grpc\STATUS_OK,
            'message' => '',
            'data' => $reply,
        ];
        return;
        */
    }

    public function actionGetwallet()
    {
        /*
        $data = new  GetListMerchantByIdResponse();
//        $data->setData($request->getCountryCode());
//        $data->setData($request->getUserId());
        return Yii::$app->response->data = [
            'status' => Grpc\STATUS_OK,
            'message' => '',
            'data' => $data,
        ];
        */
        $accoutin = new AccoutingClient();
        $req   = (new GetListMerchantByIdRequest())->setCountryCode('VN')->setUserId(23);
        $reply = $accoutin->getListMerchantById($req);

        var_dump($reply->getError());
        var_dump($reply->getData());
    }

    public function actionCheckGrpc()
    {
        $greeterClient = new Client('206.189.94.203:50054', [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
        $request = new GetListMerchantByIdRequest();
        $request->setUserId(23);
        $request->setCountryCode("VN");

        list($reply, $status) = $greeterClient->GetListMerchantById($request)->wait();
        $Merchance = $reply->getData()[0];

        //print_r($greeterClient);
        print_r($reply->getMessage());

//        echo "<pre>";
//        print_r($status);
//        echo "</pre>";

//        echo "<pre>";
//        print_r($reply->getData()[0]);
//        echo "</pre>";

        echo "<pre>";
        print_r($reply);
        echo "</pre>";

        //print_r($Merchance->getUserId());

        return $this->render('checkgrpc', []);
    }

    public function actionCreateMerchant()
    {
        $greeterClient = new Client('206.189.94.203:50054', [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
        $request = new CreateMerchantByIdRequest();
        $request->setUserId(226976);
        $request->setCountryCode("VN");
        $request->setCurrencyCode("VND");

        list($reply, $status) = $greeterClient->CreateMerchantById($request)->wait();

        echo "<pre>";
        print_r($reply);
        echo "</pre>";
        die;

        /*
        print_r($reply->getMessage());
        echo "<pre>";
        print_r($reply->getData()[0]);
        echo "</pre>";
        */

        return $this->render('createmerchant', []);
    }


    public function actionSignUp()
    {
        $greeterClient = new UserClient('206.189.94.203:50053', [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
        $request = new SignUpRequest();
        $request->setCurrency("VND");
        $request->setCountry("VN");
        $request->setEmail("weshop.test2@gmail.com");
        $request->setFullname("Jackly Hoang");
        $request->setPassword1("weshop@123");
        $request->setPassword2("weshop@123");
        $request->setPhone("0972607999");
        $request->setPlatform("WESHOP");
        $request->setPlatformUser(21);

        list($reply, $status) = $greeterClient->SignUp($request)->wait();

        echo "<pre>";
        print_r($reply->getMessage());
        echo "</pre>";
        echo "<pre>";
        print_r($reply);
        echo "</pre>";

        echo "<pre>";
        print_r($reply->getData());
        echo "</pre>";

        print_r($reply->getData()->getUserId());



        /*
        print_r($reply->getMessage());
        echo "<pre>";
        print_r($reply->getData()[0]);
        echo "</pre>";
        */

        return $this->render('createmerchant', []);
    }


    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->getId();
        $orders = Order::find()
            ->where(['=', 'customer_id', $userId])
            ->all();
        $total = count($orders);
        $greeterClient = new Client('206.189.94.203:50054', [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);
        $request = new GetListMerchantByIdRequest();
        $request->setUserId(23);
        $request->setCountryCode("VN");
        list($reply, $status) = $greeterClient->GetListMerchantById($request)->wait();
        /** @var GetListMerchantByIdResponse $reply */
        $wallet = !$reply->getError() && count($reply->getData()) ? $reply->getData()[0] : null;
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
