<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modelsMongo\ChatMongoWs;

/**
 * ChatSearch represents the model behind the search form of `common\modelsMongo\ChatMongoWs`.
 */
class ChatSearch extends ChatMongoWs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'success', 'created_at', 'updated_at', 'date', 'user_id', 'user_email', 'user_name', 'user_app', 'user_request_suorce', 'request_ip', 'message', 'user_avatars', 'Order_path', 'is_send_email_to_customer', 'type_chat', 'is_customer_vew', 'is_employee_vew'], 'safe'],
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
        $query = ChatMongoWs::find();

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
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'success', $this->success])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'user_email', $this->user_email])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_app', $this->user_app])
            ->andFilterWhere(['like', 'user_request_suorce', $this->user_request_suorce])
            ->andFilterWhere(['like', 'request_ip', $this->request_ip])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'user_avatars', $this->user_avatars])
            ->andFilterWhere(['like', 'Order_path', $this->Order_path])
            ->andFilterWhere(['like', 'is_send_email_to_customer', $this->is_send_email_to_customer])
            ->andFilterWhere(['like', 'type_chat', $this->type_chat])
            ->andFilterWhere(['like', 'is_customer_vew', $this->is_customer_vew])
            ->andFilterWhere(['like', 'is_employee_vew', $this->is_employee_vew]);

        return $dataProvider;
    }
}
