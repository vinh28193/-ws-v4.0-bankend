<?php

namespace common\modelsMongo;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modelsMongo\PaymentLogWS;


class PaymentLogWSSearch extends PaymentLogWS
{
    public function rules()
    {
        return [
            [['_id'], 'integer'],
            [[
                'created_at',
                'updated_at',
                'date',

                'user_id',
                'user_email',
                'user_name',
                'user_avatar',

                'user_app',
                'user_request_suorce',
                'request_ip',

            ], 'safe'],
            [[ 'Role','user_id','data_input','data_output', 'action_path','status' ,'LogTypPaymentWs','OrderId' ], 'required'],
        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchNew($params)
    {
        $query = PaymentLogWS::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            '_id' => $this->_id,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_email', $this->user_email])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
