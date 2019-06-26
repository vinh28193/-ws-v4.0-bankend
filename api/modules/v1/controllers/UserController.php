<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\db\AuthAssignment;
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
        ])->with(['scopeAuth']);
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
    public function actionCreate(){
        $post = \Yii::$app->request->post();
        $userNew = new User();
        $userNew->setAttributes($post,false);
        $userNew->id = null;
        $userCheck = User::find()->where(['username' => $userNew->username,'remove' => 0,'employee' => $userNew->employee])->select('id')->limit(1)->count();
        if($userCheck){
            return $this->response(false,'User name has used!');
        }
        $userCheck = User::find()->where(['email' => $userNew->email,'remove' => 0,'employee' => $userNew->employee])->select('id')->limit(1)->count();
        if($userCheck){
            return $this->response(false,'Email has used!');
        }
        $userCheck = User::find()->where(['phone' => $userNew->phone,'remove' => 0,'employee' => $userNew->employee])->select('id')->limit(1)->count();
        if($userCheck){
            return $this->response(false,'phone has used!');
        }
        if(!$userNew->validate()){
            return $this->response(false,'Validate false',$userNew->errors);
        }
        if(!isset($post['password']) || !$post['password'] || strlen($post['password']) < 8){
            return $this->response(false,'Password need 8 character');
        }
        $userNew->setPassword($post['password']);
        $userNew->generateAuthKey();
        $userNew->created_at = time();
        $userNew->updated_at = time();
        if($userNew->save()){
            if(($userNew->employee == 1 || $userNew->employee == 2) && isset($post['authAssigments']) && $post['authAssigments'] ){
                $scopes = explode(',',$post['authAssigments']);
                foreach ($scopes as $scope){
                    $assiment = new AuthAssignment();
                    $assiment->item_name = trim($scope);
                    $assiment->user_id = ''.$userNew->id;
                    $assiment->created_at = time();
                    $assiment->save();
                }
            }
            return $this->response(true,'Save user success!');
        }
        return $this->response(true,'Save user fail!',$userNew->errors);
    }
    public function actionUpdate($id){
        $post = \Yii::$app->request->post();
        $username = \Yii::$app->request->post('username');
        $email = \Yii::$app->request->post('email');
        $phone = \Yii::$app->request->post('phone');
        $userNew = User::findOne($id);
        if(!$userNew){
            return $this->response(false,'Dont see you id user: '.$id);
        }
        if($username && $userNew->username != $username){
            $userCheck = User::find()->where(['username' => $username,'remove' => 0,'employee' => $userNew->employee])->select('id')->limit(1)->count();
            if($userCheck){
                return $this->response(false,'User name has used!');
            }
        }
        if($email && $userNew->email != $email){
            $userCheck = User::find()->where(['email' => $email,'remove' => 0,'employee' => $userNew->employee])->select('id')->limit(1)->count();
            if($userCheck){
                return $this->response(false,'Email has used!');
            }
        }
        if($phone && $userNew->phone != $phone){
            $userCheck = User::find()->where(['phone' => $phone,'remove' => 0,'employee' => $userNew->employee])->select('id')->limit(1)->count();
            if($userCheck){
                return $this->response(false,'Phone has used!');
            }
        }
        $userNew->setAttributes($post,false);
        if(!$userNew->validate()){
            return $this->response(false,'Validate false',$userNew->errors);
        }
        if(isset($post['reset_pass']) && $post['reset_pass']){
            if(strlen($post['reset_pass']) < 8){
                return $this->response(false,'Password need 8 character');
            }
            $userNew->setPassword($post['reset_pass']);
        }
        $userNew->created_at = time();
        $userNew->updated_at = time();
        if($userNew->save()){
            if(($userNew->employee == 1 || $userNew->employee == 2) && isset($post['authAssigments']) && $post['authAssigments'] ){
                $scopes = explode(',',$post['authAssigments']);
                $listScope = $userNew->scopeAuth;
                foreach ($scopes as $key => $scope){
                    if(isset($listScope[$key]) && $listScope[$key]){
                        $listScope[$key]->item_name = trim($scope);
                        $listScope[$key]->save();
                    }else{
                        $assiment = new AuthAssignment();
                        $assiment->item_name = trim($scope);
                        $assiment->user_id = $userNew->id;
                        $assiment->created_at = time();
                        $assiment->save();
                    }
                }
                if(count($listScope) > count($scopes)){
                    foreach ($listScope as $key => $scope){
                        if(!isset($scopes[$key])){
                            $listScope[$key]->item_name = 'canView';
                            $listScope[$key]->save();
                        }
                    }
                }
            }
            return $this->response(true,'Save user success!');
        }
        return $this->response(true,'Save user fail!',$userNew->errors);
    }
}