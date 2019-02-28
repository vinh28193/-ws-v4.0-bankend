<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 23/02/2019
 * Time: 11:10
 */

namespace api\modules\v1\weshop\customer\controllers;


use api\modules\v1\controllers\AuthController;
use common\models\Customer;
//use common\models\Order;
use common\models\db\Order;
use Yii;

class OrderController extends AuthController
{
    public  $page = 1;
    public  $limit = 20;
    /** @var Customer $user */
    public  $user;

    public function beforeAction($action)
    {
        $parent = parent::beforeAction($action);
        $this->user = Yii::$app->user->getIdentity();
        return $parent; // TODO: Change the autogenerated stub
    }

    public function actionIndex(){
        if(isset($this->post['action'])){

        }
        print_r($this->post);
//        print_r(\Yii::$app->user->getIdentity());
        die;
    }
    public function actionGetListOrder(){
        $data = [];
        try{
            $data = $this->searchOrder();
        }catch (\Exception $exception){
            $data = $this->searchOrder();
        }
        return $this->response(true,"Success",(array)$data);
    }

    /**
     * @param string $typeorder
     * @param string $keyword
     * @param string $typeSearch
     * @param array $timeRanger = [
     *                              'time_start' => 964475495,
     *                              'time_end' => 1217889313,
     *                          ]
     * @param string $status
     * @return array|Order
     */
    private function searchOrder($typeorder = '' ,$keyword = "",$typeSearch = "",$timeRanger = [],$status = ""){
        if(!$this->user){
            return [];
        }
        $query = Order::find()->where(['customer_id' => $this->user->id,'remove' => 0,]);
        if($typeSearch){
            $query->andWhere(['like',$typeSearch,$keyword]);
        }else{
            $query->andWhere(['or',
                ['like', 'id', $keyword],
                ['like', 'seller_name', $keyword],
                ['like', 'seller_store', $keyword],
                ['like', 'portal', $keyword],
            ]);
        }
        if($typeorder){
            $query->andWhere(['type_order' => $typeorder]);
        }
        if($status){
            $query->andWhere(['current_status' => $status]);
        }
        if ($timeRanger){
            $query->andWhere(['or',
                ['>=', 'created_at', $timeRanger['time_start']],
                ['<=', 'created_at', $timeRanger['time_end']]
            ]);
        }
        return $query->orderBy('created_at desc')->limit($this->limit)->offset($this->page* $this->limit - $this->limit)->all();
    }
}