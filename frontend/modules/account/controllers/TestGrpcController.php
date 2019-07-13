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
class TestGrpcController extends BaseAccountController
{

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
        $req = (new GetListMerchantByIdRequest())->setCountryCode('VN')->setUserId(23);
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
}

?>
