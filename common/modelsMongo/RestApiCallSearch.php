<?php

namespace common\modelsMongo;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modelsMongo\RestApiCall;

/**
 * RestApiCallSearch represents the model behind the search form about `common\components\RestApiCall`.
 */
class RestApiCallSearch extends RestApiCall
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id'], 'integer'],
            [['success', 'path', 'data','date' ,
              'user_id',
              'user_email',
              'user_name',
              'user_app',
              'user_request_suorce',
              'request_ip'
                ], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = RestApiCall::find();

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
