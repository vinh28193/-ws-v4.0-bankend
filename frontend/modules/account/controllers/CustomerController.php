<?php

namespace frontend\modules\account\controllers;

use common\models\db\User;
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
                'defaultSelect' => true,
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
        $model = User::find()->where(['id' =>  $id])->with('store')->one();
        $address = Address::find()->where(['customer_id' => $id])->one();
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                $model->save();
            } if ($address) {
                $address->load(Yii::$app->request->post());
                $address->save();

            } if (!$address) {
                $add = new Address();
                $add->load(Yii::$app->request->post());
                $add->customer_id = $id;
                $add->save();
                return $this->render('index', [
                    'model' => $model,
                    'address' => $add,
                ]);
            }
        }

        return $this->render('index', [
            'model' => $model,
            'address' => $address === null ? new Address() : $address,
        ]);
    }

    public function actionVip() {
        $userId = Yii::$app->user->getIdentity()->getId();
        $models = Order::find()->with('products')->where(['customer_id' => $userId])->all();
        return $this->render('vip', [
            'models' => $models,
        ]);
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
