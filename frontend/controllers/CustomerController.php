<?php

namespace frontend\controllers;

use common\components\UserCookies;
use common\models\SystemZipcode;
use Yii;
use common\models\Customer;
use frontend\models\CustomerSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
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
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
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
     * Deletes an existing Customer model.
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
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        return $this->render('@frontend/views/common/404');
    }
    public function actionSaveAddressDefault(){
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        $userCookie = new UserCookies();
        $userCookie->setUser();
//        if(($name = ArrayHelper::getValue($post,'name'))){
//            $userCookie->name = $name;
//        }
//        if(($phone = ArrayHelper::getValue($post,'phone'))){
//            $userCookie->phone = $phone;
//        }
        if(Yii::$app->storeManager->getId() !== 7){
            if(($city_id = ArrayHelper::getValue($post,'city_id'))){
                $userCookie->province_id = $city_id;
            }else{
                return ['success'=> false,'message' => Yii::t('frontend','Enter city')];
            }
            if(($district_id = ArrayHelper::getValue($post,'district_id'))){
                $userCookie->district_id = $district_id;
            }else{
                return ['success'=> false,'message' => Yii::t('frontend','Enter district')];
            }
        }
        if(Yii::$app->storeManager->getId() == 7){
            if(($zipcode = ArrayHelper::getValue($post,'zipcode'))){
                $zip = SystemZipcode::findOne(['zip_code' => $zipcode]);
                if(!$zip){
                    return ['success'=> false,'message' => Yii::t('frontend','Zipcode not found')];
                }
                $userCookie->zipcode = $zipcode;
                $userCookie->district_id = $zip->boxme_district_id;
                $userCookie->province_id = $zip->boxme_state_province_id;
            }else{
                return ['success'=> false,'message' => Yii::t('frontend','Enter zip code')];
            }
        }
        if(!$userCookie->province_id || !$userCookie->district_id){
            return ['success'=> false,'message' => Yii::t('frontend','Save Fail')];
        }
        $userCookie->setCookies();
        return ['success'=> true,'message' => Yii::t('frontend','Save Success')];
    }
}
