<?php

namespace userbackend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\PromotionUser;

/**
 * PromotionUserSearch represents the model behind the search form of `common\models\db\PromotionUser`.
 */
class PromotionUserSearch extends PromotionUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'customer_id', 'status', 'is_used', 'used_order_id', 'used_at', 'created_at', 'promotion_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PromotionUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'store_id' => $this->store_id,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'is_used' => $this->is_used,
            'used_order_id' => $this->used_order_id,
            'used_at' => $this->used_at,
            'created_at' => $this->created_at,
            'promotion_id' => $this->promotion_id,
        ]);

        return $dataProvider;
    }
}
