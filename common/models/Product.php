<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-07
 * Time: 13:42
 */

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\db\Product as DbProduct;
use common\models\queries\ProductQuery;
use common\components\AdditionalFeeTrait;

/**
 * Class Product
 * @package common\models
 */
class Product extends DbProduct
{
    use AdditionalFeeTrait;

//    public function behaviors()
//    {
//        return ArrayHelper::merge(parent::behaviors(),[
//            'ProductFee' => [
//                'class' => \common\behaviors\AdditionalFeeBehavior::className(),
//            ],
//        ]);
//    }


    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(ProductQuery::className(), [get_called_class()]);
    }


    // Optional sort/filter params: page,limit,Product,search[name],search[email],search[id]... etc

    static public function search($params)
    {

        $page = Yii::$app->getRequest()->getQueryParam('page');
        $limit = Yii::$app->getRequest()->getQueryParam('limit');
        $Product = Yii::$app->getRequest()->getQueryParam('Product');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if(isset($search)){
            $params=$search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;

        $query = Product::find()
            //->withFullRelations()
            ->filter($params)
            ->limit($limit)
            ->offset($offset);

        if(isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }

        if(isset($params['created_at'])) {
            $query->andFilterWhere(['created_at' => $params['created_at']]);
        }
        if(isset($params['updated_at'])) {
            $query->andFilterWhere(['updated_at' => $params['updated_at']]);
        }
        if(isset($params['receiver_email'])){
            $query->andFilterWhere(['like', 'receiver_email', $params['receiver_email']]);
        }

        /*

        if(isset($params['typeSearch']) and isset($params['keyword']) ){
            $query->andFilterWhere(['like',$params['typeSearch'],$params['keyword']]);
        }else{
            $query->andWhere(['or',
                ['like', 'id', $params['keyword']],
                ['like', 'seller_name', $params['keyword']],
                ['like', 'seller_store', $params['keyword']],
                ['like', 'portal', $params['keyword']],
            ]);
        }
        */

        if(isset($params['type_Product'])){
            $query->andFilterWhere(['type_Product' => $params['type_Product'] ]);
        }
        if(isset($params['current_status'])){
            $query->andFilterWhere(['current_status' => $params['current_status']]);
        }
        if (isset($params['time_start']) and isset($params['time_end']) ){
            $query->andFilterWhere(['or',
                ['>=', 'created_at', $params['time_start']],
                ['<=', 'updated_at', $params['time_end']]
            ]);
        }

        if(isset($Product)){
            $query->ProductBy($Product);
        }


        if(isset($Product)){
            $query->ProductBy($Product);
        }

        $additional_info = [
            'page' => $page,
            'size' => $limit,
            'totalCount' => (int)$query->count()
        ];

        $data = (array)$query->all();
        return array_merge($data , $additional_info);

    }
}
