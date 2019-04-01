<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-30
 * Time: 09:50
 */

namespace common\products\forms;

use yii\helpers\ArrayHelper;

class ProductSearchForm extends BaseForm
{

    const SCENARIO_EBAY = 'ebay';

    const SCENARIO_AMAZON = 'amazon';

    /**
     * public global key search
     * available all gates
     * @var string
     */
    public $keyword;
    /**
     * public global category
     * available all gates
     * @var string
     */
    public $category;
    /**
     * public global filter
     * available all gates
     * note: check diff with each gate
     * @var string
     */
    public $filter;
    /**
     * product_type / dau gia/ shop .. etc.
     * only ebay gate
     * @var integer
     */

    public $product_type;
    /**
     * type
     * only ebay gate
     * @var integer
     */

    public $seller;
    /**
     * min price
     * only ebay gate
     * @var integer
     */
    public $min_price;
    /**
     * max price
     * only ebay gate
     * @var integer
     */
    public $max_price;
    /**
     * sort
     * only ebay gate
     * @var string
     */
    public $sort;
    /**
     * @var string
     */
    public $page;

    /**
     * only ebay gate
     * @var string
     */
    public $per_page;

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_EBAY => [
                'keyword', 'category', 'filter', 'seller', 'min_price', 'product_type', 'max_price', 'sort', 'page', 'per_page'
            ],
            self::SCENARIO_AMAZON => [
                'keyword', 'category', 'filter', 'sort', 'page'
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'keyword', 'category', 'filter', 'min_price', 'seller', 'product_type', 'max_price', 'sort', 'page', 'per_page'
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['keyword'], 'required'],
            [['category', 'page'], 'integer'],
            [['min_price', 'max_price', 'product_type'], 'integer'],
            [['keyword', 'filter'], 'string'],
            [['seller', 'sort'], 'string'],
        ]);
    }

    public function setScenarioByType()
    {
        $scn = self::SCENARIO_AMAZON;
        if ($this->type === 'ebay') {
            $scn = self::SCENARIO_EBAY;
        }
        $this->setScenario($scn);
    }

    public function search($refresh = false)
    {
        /** @var $success boolean */
        /** @var $product mixed */
        $this->setScenarioByType();
        list($success, $rs) = $this->getActiveGate()->search($this->getParams(), $refresh);
        if (!$success && is_string($rs)) {
            $this->addError('keyword', $product);
            return false;
        }
        return $rs;
    }

    public function getParams()
    {
        $params = [];
        foreach ($this->activeAttributes() as $activeAttribute) {
            if (($value = $this->$activeAttribute) === null || ($this->getScenario() === self::SCENARIO_EBAY && $activeAttribute === 'type')) {
                continue;
            }
            if ($activeAttribute === 'product_type') {
                $activeAttribute = 'type';
            } elseif ($activeAttribute === 'seller') {
                $activeAttribute = 'sellers';
            }
            $params[$activeAttribute] = $value;
        }
        return $params;
    }
}