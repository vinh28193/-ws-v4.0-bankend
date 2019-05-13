<?php

namespace frontend\models;

use common\models\Address;
use common\models\Store;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `common\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'email_verified', 'phone_verified', 'type_customer', 'store_id', 'active_shipping', 'total_xu_start_date', 'total_xu_expired_date', 'usable_xu_start_date', 'usable_xu_expired_date', 'last_use_time', 'last_revenue_time', 'verify_code_expired_at', 'verify_code_count', 'created_at', 'updated_at', 'active', 'remove'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone', 'username', 'password_hash', 'birthday', 'avatar', 'link_verify', 'last_order_time', 'note_by_employee', 'access_token', 'auth_client', 'verify_token', 'reset_password_token', 'verify_code', 'verify_code_type', 'version'], 'safe'],
            [['total_xu', 'usable_xu', 'last_use_xu', 'last_revenue_xu'], 'number'],
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
        $query = Customer::find();

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
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'email_verified' => $this->email_verified,
            'phone_verified' => $this->phone_verified,
            'last_order_time' => $this->last_order_time,
            'type_customer' => $this->type_customer,
            'store_id' => $this->store_id,
            'active_shipping' => $this->active_shipping,
            'total_xu' => $this->total_xu,
            'total_xu_start_date' => $this->total_xu_start_date,
            'total_xu_expired_date' => $this->total_xu_expired_date,
            'usable_xu' => $this->usable_xu,
            'usable_xu_start_date' => $this->usable_xu_start_date,
            'usable_xu_expired_date' => $this->usable_xu_expired_date,
            'last_use_xu' => $this->last_use_xu,
            'last_use_time' => $this->last_use_time,
            'last_revenue_xu' => $this->last_revenue_xu,
            'last_revenue_time' => $this->last_revenue_time,
            'verify_code_expired_at' => $this->verify_code_expired_at,
            'verify_code_count' => $this->verify_code_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'active' => $this->active,
            'remove' => $this->remove,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'link_verify', $this->link_verify])
            ->andFilterWhere(['like', 'note_by_employee', $this->note_by_employee])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'auth_client', $this->auth_client])
            ->andFilterWhere(['like', 'verify_token', $this->verify_token])
            ->andFilterWhere(['like', 'reset_password_token', $this->reset_password_token])
            ->andFilterWhere(['like', 'verify_code', $this->verify_code])
            ->andFilterWhere(['like', 'verify_code_type', $this->verify_code_type])
            ->andFilterWhere(['like', 'version', $this->version]);

        return $dataProvider;
    }

}
