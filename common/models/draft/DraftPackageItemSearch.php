<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-03
 * Time: 11:10
 */

namespace common\models\draft;

use common\data\ActiveDataProvider;
use yii\base\Model;

class DraftPackageItemSearch extends DraftPackageItem
{

    /**
     * @var string
     */
    public $keyword;

    /**
     * @var array
     */
    private $_message;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['keyword', 'safe'],
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
        $query = DraftPackageItem::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'ps',
                'pageParam' => 'p',
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        //$query->where(['tracking_code' => $this->keyword]);
        return $dataProvider;
    }

    public function createResponseMessage()
    {
        return "success";
    }
}