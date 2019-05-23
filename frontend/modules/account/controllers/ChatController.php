<?php

namespace frontend\modules\account\controllers;

use Yii;
use common\modelsMongo\ChatMongoWs;
use frontend\models\ChatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChatController implements the CRUD actions for ChatMongoWs model.
 */
class ChatController extends Controller
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
     * Lists all ChatMongoWs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->getId();
        $now = Yii::$app->getFormatter()->asDatetime('now');
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        $_request_ip = Yii::$app->getRequest()->getUserIP();
        $model = ChatMongoWs::find()->where(['Order_path' => $get['ordercode']])->all();
        if ($post) {
            $query = new ChatMongoWs();
            $query->message = $post['message'];
            $query->Order_path = $get['ordercode'];
            $query->request_ip = $_request_ip;
            $query->user_email = Yii::$app->user->identity->email;
            $query->user_name = Yii::$app->user->identity->username;
            $query->date = $now;
            $query->type_chat = 'CUSTOMER_WS';
            $query->user_app  = 'Weshop2019';
            $query->save();
        }
        return $this->renderAjax('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single ChatMongoWs model.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        $userId = Yii::$app->user->getId();
        $get = Yii::$app->request->get();
        $model = ChatMongoWs::find()->where([
            'and',
            ['Order_path' => $get['ordercode']],
            ['user_id' => $userId]
        ])->all();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ChatMongoWs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChatMongoWs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ChatMongoWs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ChatMongoWs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ChatMongoWs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return ChatMongoWs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChatMongoWs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
