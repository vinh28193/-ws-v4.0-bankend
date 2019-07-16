<?php

namespace frontend\modules\account\controllers;

use common\models\SystemDistrict;
use common\models\SystemStateProvince;
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
        return ArrayHelper::merge(parent::actions(), [
            'sub-district' => [
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
                $model->email = ArrayHelper::getValue($userPost,'email');
                $model->phone = ArrayHelper::getValue($userPost,'phone');
                $model->save();
                $district = SystemDistrict::findOne(ArrayHelper::getValue($addressPost,'district_id'));
                $province = SystemStateProvince::findOne(ArrayHelper::getValue($addressPost,'province_id'));
                if ($address) {
                    $address->first_name = ArrayHelper::getValue($userPost,'first_name',$model->first_name);
                    $address->email = ArrayHelper::getValue($userPost,'email');
                    $address->phone = ArrayHelper::getValue($userPost,'phone');
                    $address->province_id = ArrayHelper::getValue($addressPost,'province_id');
                    $address->district_name = $district->name;
                    $address->province_name = $province->name;
                    $address->district_id = ArrayHelper::getValue($addressPost,'district_id');
                    $address->address = ArrayHelper::getValue($addressPost,'address');
                    $address->save();
                } else{
                    $add = new Address();
                    $add->first_name = ArrayHelper::getValue($userPost,'first_name',$model->first_name);
                    $add->email = ArrayHelper::getValue($userPost,'email');
                    $add->phone = ArrayHelper::getValue($userPost,'phone');
                    $add->province_id = ArrayHelper::getValue($addressPost,'province_id');
                    $add->district_id = ArrayHelper::getValue($addressPost,'district_id');
                    $add->address = ArrayHelper::getValue($addressPost,'address');
                    $add->type = Address::TYPE_PRIMARY;
                    $add->is_default = 1;
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
        $post = Yii::$app->request->post();
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
        $address->district_id = Yii::$app->request->post('district_id');
        $address->province_id = Yii::$app->request->post('province_id');
        $address->address = Yii::$app->request->post('address');
        $address->post_code = Yii::$app->request->post('zip_code');
        $address->customer_id = $user->id;
        $address->remove = 0;
        $address->type = Address::TYPE_SHIPPING;
        $address->is_default = Yii::$app->request->post('is_default') ? 1 : 0;
        $district = SystemDistrict::findOne(Yii::$app->request->post('district_id'));
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

        Yii::info("Call Level Boxme ");
        $curl = new Curl();
        $paramPost = [
            'email' => $username,
            'password' => $password,
            'platform' => 'Weshop',
            'country' => $this->storeManager->store->country_code
        ];
        $Json_string = '{"email": "'.$username.'", "password": "'.$password.'","country":"'. $this->storeManager->store->country_code.'","platform":"Weshop"}';

        Yii::info(" Param send Connect Boxme : ");
        $api_login_boxme = $api_addresse_warehouse = '';
        if (YII_ENV == 'prod') {
            $api_login_boxme  = Yii::$app->params['api_login_boxme'] ? Yii::$app->params['api_login_boxme'] : 'https://s.boxme.asia/api/v1/users/auth/sign-in/';
            $api_addresse_warehouse = Yii::$app->params['api_addresse_warehouse'] ? Yii::$app->params['api_addresse_warehouse'] : 'https://s.boxme.asia/api/v1/sellers/addresses/default-warehouse/';
        }elseif ( $api_login_boxme == '' and  !YII_ENV == 'prod'){
            $api_login_boxme  = Yii::$app->params['api_login_boxme'] ? Yii::$app->params['api_login_boxme'] : 'http://sandbox.boxme.asia/api/v1/users/auth/sign-in/';
            $api_addresse_warehouse = Yii::$app->params['api_addresse_warehouse'] ? Yii::$app->params['api_addresse_warehouse'] : 'http://sandbox.boxme.asia/api/v1/sellers/addresses/default-warehouse/';
        }

        Yii::info("Curl send call / get Level Boxme + Phone : ");
        $response = $curl->setHeaders([ 'Content-Type' => 'application/json',  ])
                         ->setRawPostData($Json_string)
                         ->post($api_login_boxme,true);
        $dataRs = @json_decode($response,true);
        Yii::info([
            'Curl'=> 'Curl send call / get Level Boxme + Phone :',
            'paramPost' =>$paramPost,
            'Json' =>@json_encode($paramPost),
            '$Json_string' => $Json_string,
            'curl' => @unserialize($curl),
            'response' => @unserialize($response),
            '$api_login_boxme' => $api_login_boxme,
            'dataRs' =>$dataRs,
        ], __CLASS__);

        if($dataRs['error']){
            return ['success' => false, 'data' => ['password' => $dataRs['messages']]];
        }else{
            if($dataRs['data']['active'] == 1 && $dataRs['data']['id']){
                $checkImp = User::find()->where([
                      'bm_wallet_id' => $dataRs['data']['user']['id'],
                      'id' => $user->id,
                ])->select('id')->count();

                if($checkImp > 0){
                    return ['success' => false, 'data' => ['password' => Yii::t('frontend','This Boxme account was connected with other Weshop account.')]];
                }
                $user->bm_wallet_id = $dataRs['data']['id'];
                if(isset($dataRs['data']['loyalty']) && isset($dataRs['data']['loyalty']['user_level'])&& isset($dataRs['data']['loyalty']['time_end'])){
                    $user->vip_end_time = $dataRs['data']['loyalty']['time_end'];

                    /** Check Time End **/
                    if($dataRs['data']['loyalty']['time_end'] == 0){
                        $user->vip = $dataRs['data']['loyalty']['user_level'];
                    }elseif ($dataRs['data']['loyalty']['time_end'] > 0 )
                    { $user->vip = $dataRs['data']['loyalty']['time_end'] >= time() ? $dataRs['data']['loyalty']['user_level'] : 0;   }

                    $user->bm_wallet_id =  $dataRs['data']['user']['id'];
                    Yii::info("User  : ". $user->email . ' set level : '.$dataRs['data']['loyalty']['user_level'] . '. Time end  level :'. $dataRs['data']['loyalty']['time_end']);

                    // ToDo Địa Chỉ kho cho mỗi khách thuộc loại hạng 1 hoặc 2
                    // http://sandbox.boxme.asia/api/v1/sellers/addresses/default-warehouse/
                    $Json_string_Address = '{
                      "token": "8f6df519a2125946820bc34a561164c2",
                      "country": "'.$this->storeManager->store->country_code.'",
                      "user_id": '.$dataRs['data']['user']['id'].',
                      "phone": "'.$dataRs['data']['phone_number'].'",
                      "fullname": "'.$user->first_name .' '.$user->last_name.'"
                    }';
                    Yii::info("Curl send call get Address : ");
                    $curl_addess = new Curl();
                    $response_res = $curl_addess->setHeaders([ 'Content-Type' => 'application/json',  ])
                        ->setRawPostData($Json_string_Address)
                        ->post($api_addresse_warehouse,true);
                    $dataRs_add = @json_decode($response_res,true);

                    Yii::info([
                        'Curl'=> 'Curl send call get Address :',
                        'paramPost' =>$paramPost,
                        'Json' =>@json_encode($paramPost),
                        '$Json_string' => $Json_string_Address,
                        'curl' => @unserialize($curl_addess),
                        'response' => @unserialize($response_res),
                        '$api_login_boxme' => $api_addresse_warehouse,
                        'dataRs' =>$dataRs_add,
                    ], __CLASS__);

                    $warehouse_code = ""; //  "BMID_US";
                    $pickup_id = 0;
                    if($dataRs_add['error']){
                        return ['success' => false, 'data' => ['password' => 'Error : ' . $dataRs_add['messages']]];
                    }else {
                        if(!empty($dataRs_add['data'])){
                            foreach ($dataRs_add['data'] as $key =>$value){
                                if ($this->storeManager->store->country_code == 'VN' and $value['ff_center_code'] == "BMVN_US") {
                                    $warehouse_code = $value['ff_center_code'];
                                    $pickup_id = $value['id'];
                                }
                                if ($this->storeManager->store->country_code == 'ID' and $value['ff_center_code'] == "BMID_US"){
                                    $warehouse_code = $value['ff_center_code'];
                                    $pickup_id = $value['id'];
                                }
                            }
                        $user->pickup_id = $pickup_id;
                        $user->warehouse_code = $warehouse_code;
                         Yii::info("Set-get address Id Warehouse");
                         Yii::info([
                            'warehouse_code' => $warehouse_code,
                            'pickup_id' => $pickup_id
                         ],__CLASS__);

                        }
                    }//end Curl send call get Address

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
        $user->pickup_id = 0;
        $user->warehouse_code = '';
        $user->save();
        return ['success' => true];
    }
}
