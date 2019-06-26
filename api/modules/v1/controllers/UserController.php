<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\User;

class UserController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','view','delete','update','create'],
                'roles' => ['superAdmin']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
        ];
    }
    public function actionIndex(){
        $limit = \Yii::$app->request->get('limit',20);
        $page = \Yii::$app->request->get('page',1);
        $email = \Yii::$app->request->get('email');
        $type = \Yii::$app->request->get('type');
        $phone = \Yii::$app->request->get('phone');
        $userName = \Yii::$app->request->get('username');
        $id = \Yii::$app->request->get('id');
        $query = User::find()->select([
            'id',
            'bm_wallet_id',
            'created_at',
            'store_id',
            'locale',
            'first_name',
            'last_name',
            'type_customer',
            'vip',
            'uuid',
            'token_fcm',
            'token_apn',
            'facebook_acc_kit_id',
            'username',
            'email',
            'phone',
            'employee',
            'status',
            'remove',
        ])->with(['authAssigments']);
        if($email){
            $query->andWhere(['like','email',$email]);
        }
        if($id){
            $query->andWhere(['id'=>$id]);
        }
        if($type){
            $query->andWhere(['employee'=>$type]);
        }elseif ($type != null){
            $query->andWhere(['or',['employee' => $type],['is' ,'employee',null]]);
        }
        if($phone){
            $query->andWhere(['like','phone',$phone]);
        }
        if($userName){
            $query->andWhere(['like','username',$userName]);
        }
        $data = $query->limit($limit)->offset($limit * $page - $limit)->orderBy('id desc')->asArray()->all();
        return $this->response(true,'Success',$data);
    }
}