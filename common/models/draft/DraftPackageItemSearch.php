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
        if (!$this->load($params) || !$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->where(['tracking_code' => $this->keyword]);
        return $dataProvider;
    }

    public function createResponseMessage(){
        return "success";
    }
}