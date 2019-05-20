<?php


namespace frontend\modules\checkout\controllers;


use common\components\cart\CartManager;
use frontend\modules\payment\providers\wallet\WalletService;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use Yii;
use common\components\cart\CartHelper;
use frontend\modules\payment\models\ShippingForm;
use frontend\modules\payment\Payment;
use common\models\SystemStateProvince;
use common\components\cart\CartSelection;
use yii\helpers\ArrayHelper;

class ShippingController extends CheckoutController
{

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'sub-district' => [
                'class' => 'common\actions\DepDropAction',
                'defaultSelect' => true,
                'useAction' => 'common\models\SystemDistrict::select2Data'
            ]
        ]);
    }

    public function actionIndex($type)
    {
        $activeStep = 2;
        $isGuest = Yii::$app->user->isGuest;
        if ($isGuest) {
            $activeStep = 1;
        }
        if (($keys = CartSelection::getSelectedItems($type)) === null) {
            return $this->goBack();
        }
        $payment = new Payment([
            'page' => Payment::PAGE_CHECKOUT,
            'carts' => $keys,
            'payment_type' => $type,
        ]);
        $payment->initDefaultMethod();
        $shippingForm = new ShippingForm();
        $shippingForm->setDefaultValues();
        $provinces = SystemStateProvince::select2Data(1);
        return $this->render('index', [
            'activeStep' => $activeStep,
            'shippingForm' => $shippingForm,
            'payment' => $payment,
            'provinces' => $provinces
        ]);
    }
    public function actionLogin(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            return ['success' => true];
        }
        $model = new LoginForm();
        $model->rememberMe = false; // Mặc định không ghi nhớ
        if ($model->load(Yii::$app->request->post()) && $model->login() ) {
            $key = CartSelection::getSelectedItems(CartSelection::TYPE_BUY_NOW);
            if($key){
                $this->module->cartManager->setMeOwnerItem($key[0]);
            }
            $wallet = new WalletService();
            $wallet->login($model->password);
            return ['success' => true, 'message' => 'đăng nhập thành công'];
        } else {
            return ['success' => false, 'message' => 'đăng nhập thất bại' , 'data' => $model->errors];
        }
    }
    public function actionSignup(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::info('register new');
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::info('register new 002');
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                $model->sendEmail();
                Yii::info('register new 003');
                if(Yii::$app->getUser()->login($user)){
                    $key = CartSelection::getSelectedItems(CartSelection::TYPE_BUY_NOW);
                    if($key){
                        $this->module->cartManager->setMeOwnerItem($key[0]);
                    }
                    $wallet = new WalletService();
                    $wallet->login($model->password);
                    return ['success' => true, 'message' => 'đăng ký thành công'];
                }
            }
        }
        return ['success' => false, 'message' => 'đăng ký thất bại' , 'data' => $model->errors];
    }

}