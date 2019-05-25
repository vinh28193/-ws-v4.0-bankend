<?php

namespace frontend\modules\account\controllers;

use common\models\User;
use common\models\Address;
use common\models\Order;
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
            ['type' => Address::TYPE_SHIPPING],
//            ['is_default' => Address::IS_DEFAULT]
        ])->all();
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
