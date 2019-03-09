<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 17:26
 */

namespace common\models\queries;

use yii\db\ActiveQuery;

class OrderQuery extends ActiveQuery
{

    /**
     * @param $params
     * @return $this
     */
    public function filter($params){

        return $this;
    }

    public function withFullRelations(){
        $this->with([
            'products',
            'orderFees',
            'packageItems',
            'walletTransactions',
            'seller',
            'saleSupport' => function ($q) {
                /** @var ActiveQuery $q */
                $q->select(['username','email','id','status', 'created_at', 'updated_at']);
            }
        ]);
        return $this;
    }
}