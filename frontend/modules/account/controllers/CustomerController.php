<?php

namespace frontend\modules\account\controllers;

use common\models\SystemDistrict;
use common\models\User;
use common\models\Address;
use common\models\Order;
use frontend\modules\account\views\widgets\FormAddressWidget;
use linslin\yii2\curl\Curl;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for User model.
 */
class CustomerController extends BaseAccountController
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

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(),[
            'subcat' => [
                'class' => 'common\actions\DepDropAction',
                'useAction' => 'common\models\SystemDistrict::select2Data'
            ]
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $id = Yii::$app->user->getIdentity()->getId();
        /** @var User $model */
        $model = User::find()->where(['id' =>  $id])->with('store')->one();
        /** @var Address $address */
        $address = Address::find()->where([
            'AND',
            ['customer_id' => $id],
            ['type' => Address::TYPE_PRIMARY],
            ['is_default' => Address::IS_DEFAULT]

        ])->one();
        $addressShip = Address::find()->where([
            'AND',
            ['customer_id' => $id],
            ['remove' => 0],
            ['type' => Address::TYPE_SHIPPING],
//            ['is_default' => Address::IS_DEFAULT]
        ])->orderBy('is_default desc, id desc')->all();
        if(Yii::$app->request->isPost){
            $userPost = Yii::$app->request->post('User');
            $addressPost = Yii::$app->request->post('Address');
            if ($model && $userPost) {
                $model->first_name = ArrayHelper::getValue($userPost,'first_name',$model->first_name);
                $model->last_name = ArrayHelper::getValue($userPost,'last_name',$model->last_name);
                $model->email = $model->email ? $model->email : ArrayHelper::getValue($userPost,'email');
                $model->phone = $model->phone ? $model->phone : ArrayHelper::getValue($userPost,'phone');
                $model->save();
                if ($address) {
                    $address->first_name = ArrayHelper::getValue($userPost,'first_name',$model->first_name);
                    $address->last_name = ArrayHelper::getValue($userPost,'last_name',$model->last_name);
                    $address->email = ArrayHelper::getValue($userPost,'email');
                    $address->phone = ArrayHelper::getValue($userPost,'phone');
                    $address->province_id = ArrayHelper::getValue($addressPost,'province_id');
                    $address->district_id = ArrayHelper::getValue($addressPost,'district_id');
                    $address->address = ArrayHelper::getValue($addressPost,'address');
                    $address->save();
                } else{
                    $add = new Address();
                    $add->first_name = ArrayHelper::getValue($userPost,'first_name',$model->first_name);
                    $add->last_name = ArrayHelper::getValue($userPost,'last_name',$model->last_name);
                    $add->email = ArrayHelper::getValue($userPost,'email');
                    $add->phone = ArrayHelper::getValue($userPost,'phone');
                    $add->province_id = ArrayHelper::getValue($addressPost,'province_id');
                    $add->district_id = ArrayHelper::getValue($addressPost,'district_id');
                    $add->address = ArrayHelper::getValue($addressPost,'address');
                    $add->save();
                    return $this->render('index', [
                        'model' => $model,
                        'address' => $add,
                        'addressShip' => $addressShip,
                    ]);
                }
            }
        }

        return $this->render('index', [
            'model' => $model,
            'address' => $address === null ? new Address() : $address,
            'addressShip' => $addressShip,
        ]);
    }

    public function actionVip() {
        $userId = Yii::$app->user->getIdentity()->getId();
        $models = Order::find()->with('products')->where(['customer_id' => $userId])->all();
        return $this->render('vip', [
            'models' => $models,
        ]);
    }

    public function actionSaved() {
        return $this->render('saved-products');
    }

//    public function actionSubcat() {
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        $out = [];
//        if (isset($_POST['depdrop_parents'])) {
//            $parents = $_POST['depdrop_parents'];
//            if ($parents != null) {
//                $cat_id = (int)$parents[0];
//                $out = SystemDistrict::filterDistrictByProvinceId($cat_id);
//                if(!empty($out)){
//                    return ['output'=>$out, 'selected'=> $out[0]['id']];
//                }
//
//            }
//        }
//        return ['output'=>'', 'selected'=>''];
//    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = User::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('frontend','The requested page does not exist.'));
    }

    public function actionSaveAddressShipping() {
        Yii::$app->response->format = 'json';
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        if(!$user || Yii::$app->user->isGuest){
            return ['success' => false, 'message' => Yii::t('frontend','Please login.')];
        }
        $idAddress = Yii::$app->request->post('idAddress');
        if($idAddress){
            $address = Address::findOne($idAddress);
            if(!$address){
                return ['success' => false, 'message' => Yii::t('frontend','Address not found.')];
            }
        }else{
            $address = new Address();
        }
        $address->first_name = Yii::$app->request->post('fullName');
        $address->phone = Yii::$app->request->post('phone');
        $address->email = Yii::$app->request->post('email');
        $address->district_id = Yii::$app->request->post('district');
        $address->province_id = Yii::$app->request->post('province');
        $address->address = Yii::$app->request->post('address');
        $address->post_code = Yii::$app->request->post('zip_code');
        $address->customer_id = $user->id;
        $address->remove = 0;
        $address->type = Address::TYPE_SHIPPING;
        $address->is_default = Yii::$app->request->post('is_default') ? 1 : 0;
        $district = SystemDistrict::findOne($address->district_id);
        if(!$district || $district->province_id != $address->province_id){
            return ['success' => false, 'message' => Yii::t('frontend','District or province not found.')];
        }
        $address->district_name = $district->name;
        $address->province_name = $district->province->name;
        $address->country_name = $district->country->name;
        $address->country_id = $district->country_id;
        $address->store_id = $user->store_id;
        $errors = $address->validateAddress();
        if($address->is_default == 1){
            Address::updateAll(['is_default' => 0],['is_default' => 1 , 'customer_id' => $user->id , 'type' => Address::TYPE_SHIPPING]);
        }
        if($errors && count($errors) > 0){
            return ['success' => false, 'message' => Yii::t('frontend','Add shipping address fail!'), 'errors' => $errors];
        }
        if($address->save()){
            return ['success' => true, 'message' => Yii::t('frontend','Add shipping address success!')];
        }
        return ['success' => false, 'message' => Yii::t('frontend','Add shipping address fail!'), 'errors' => $errors];
    }
    public function actionEditAddress() {
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        if(!$user || Yii::$app->user->isGuest){
            return ['success' => false, 'message' => Yii::t('frontend','Please login.')];
        }
        $address = Address::findOne(['id' => $id, 'customer_id' => $user->id, 'remove' => 0]);
        if(!$address){
            return ['success' => false, 'message' => Yii::t('frontend','Address not found.')];
        }
        $content = FormAddressWidget::widget(['address' => $address]);
        $title = Yii::t('frontend','Edit shipping address');
        return ['success' => true, 'message' => Yii::t('frontend','Get success.') , 'data' => ['content' => $content,'title' => $title]];
    }
    public function actionRemoveAddress() {
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->post('id');
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        if(!$user || Yii::$app->user->isGuest){
            return ['success' => false, 'message' => Yii::t('frontend','Please login.')];
        }
        $address = Address::findOne(['id' => $id, 'customer_id' => $user->id, 'remove' => 0]);
        if(!$address){
            return ['success' => false, 'message' => Yii::t('frontend','Address not found.')];
        }
        $address->remove = 1;
        $address->save();
        return ['success' => true, 'message' => Yii::t('frontend','Delete success.')];
    }
    public function actionConnectBoxme() {
        Yii::$app->response->format = 'json';
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        Yii::info("User Connect Boxme : ");
        Yii::info([
            'User' =>$user,
        ], __CLASS__);
        if(!$user){
            return ['success' => false, 'message' => Yii::t('frontend','Please login account weshop!') ,'data' => ['email' => Yii::t('frontend','Please login account weshop!')]];
        }
        if($user->bm_wallet_id){
            return ['success' => false, 'message' => Yii::t('frontend','You are already connected to a boxme account.') ,'data' => ['email' => Yii::t('frontend','You are already connected to a boxme account')]];
        }
        if(!$username){
            return ['success' => false, 'data' => ['email' => Yii::t('frontend','Email cannot null!')]];
        }
        if(!$password){
            return ['success' => false, 'data' => ['password' => Yii::t('frontend','Password cannot null!')]];
        }
        $curl = new Curl();
        $paramPost = [
            'email' => $username,
            'password' => $password,
            'platform' => 'Weshop',
            'country' => $this->storeManager->store->country_code
        ];
        Yii::info("User send Connect Boxme : ");
        Yii::info([
            'paramPost' =>$paramPost,
            'Json' =>@json_encode($paramPost),
        ], __CLASS__);
        $response = $curl->setHeaders([
                            'Content-Type' => 'application/json',
                        ])
                         ->setRawPostData('{
  "email": "ws_test@gmail.com",
  "password": "123456a@",
  "country": "VN",
  "platform": "Weshop"
}')
                         ->post('https://s.boxme.asia/api/v1/users/auth/sign-in/',true);
                    // ->post(ArrayHelper::getValue(Yii::$app->params,'api_login_boxme','https://s.boxme.asia/api/v1/users/auth/sign-in/'));


        Yii::info([
            'curl' => @unserialize($curl),
            'response' => @unserialize($response),
        ], __CLASS__);

        $dataRs = @json_decode($response,true);
        Yii::info([
            'dataRs' =>$dataRs,
        ], __CLASS__);
        if($dataRs['error']){
            return ['success' => false, 'data' => ['password' => $dataRs['messages']]];
        }else{
            if($dataRs['data']['active'] == 1 && $dataRs['data']['id']){
                $checkImp = User::find()->where(['bm_wallet_id' => $dataRs['data']['id'],''])->select('id')->count();
                if($checkImp > 0){
                    return ['success' => false, 'data' => ['password' => Yii::t('frontend','This Boxme account was connected with other Weshop account.')]];
                }
                $user->bm_wallet_id = $dataRs['data']['id'];
                if(isset($dataRs['data']['loyalty']) && isset($dataRs['data']['loyalty']['user_level'])&& isset($dataRs['data']['loyalty']['time_end'])){
                    $user->vip_end_time = $dataRs['data']['loyalty']['time_end'];
                    $user->vip = $dataRs['data']['loyalty']['time_end'] >= time() ? $dataRs['data']['loyalty']['user_level'] : 0;
                }else{
                    $user->vip = 0;
                }
                $user->save();
                return ['success' => true, 'message' => Yii::t('frontend','Connect success. Please wait 15 seconds.')];
            }
            return ['success' => false, 'data' => ['password' => Yii::t('frontend','Has error.')]];
        }
    }
    public function actionDisconnectBoxme() {
        Yii::$app->response->format = 'json';
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        if(!$user){
            return ['success' => false, 'message' => Yii::t('frontend','Please login account weshop!') ,'data' => ['email' => Yii::t('frontend','Please login account weshop!')]];
        }
        $user->bm_wallet_id = null;
        $user->vip_end_time = null;
        $user->vip = 0;
        $user->save();
        return ['success' => true];
    }
}
