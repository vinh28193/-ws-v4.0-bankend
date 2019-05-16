<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-24
 * Time: 17:20
 */

namespace wallet\modules\v1\models\search;
use Yii;
use yii\base\Model;
use wallet\modules\v1\models\WalletTransaction;

class WalletTransactionSearch extends WalletTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = WalletTransaction::find();
        return $query->all();
    }

}