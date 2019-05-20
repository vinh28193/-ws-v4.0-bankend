<?php


namespace frontend\modules\account\controllers\api;


use common\payment\providers\wallet\WalletService;
use yii\base\Controller;
use yii\web\Response;

class WalletServiceController extends Controller
{
    public function actionLoginWallet(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $password = \Yii::$app->request->post('password');
        if($password){
            return (new WalletService())->login($password);
        }
        return ['success' => false , 'message' => 'Mật khẩu không đúng'];
    }
}