<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 17:00
 */

namespace common\products\ebay;

use common\products\BaseResponse;
use Yii;
use yii\helpers\ArrayHelper;

class EbaySearchResponse extends BaseResponse
{

    /**
     * EbayDetailResponse constructor.
     * @param EbayGate $gate
     * @param array $config
     */
    public function __construct(EbayGate $gate, array $config = [])
    {
        parent::__construct($gate, $config);
    }

    /**
     * @param $response
     * @return array|\common\products\BaseProduct|null
     */
    public function parser($response)
    {
        if (!$this->isEmpty($response['data']) && $response['success'] !== false) {
            $result = $response['data'];
            foreach ($result['products'] as $key => $val) {
                if ($this->isBanned($val['item_id'])) {
                    unset($result['products'][$key]);
                } else {
                    $ebay = new EbayProduct();
                    $ebay->sell_price = $val['sell_price'];
                    $ebay->start_price = $val['retail_price'];
                    $ebay->shipping_weight = 1;
                    $ebay->category_id = $this->isAliasAvailable($val['category_id']) ? $val['category_id'] : $result['categories'][0]['category_id'];
                    $ebay->init();
                    $result['products'][$key]['sale_price_local'] = $ebay->getStoreManager()->showMoney($ebay->getLocalizeTotalPrice());
                    $result['products'][$key]['start_price_local'] = $ebay->getStoreManager()->showMoney($ebay->getLocalizeTotalStartPrice());
//                    if (ArrayHelper::isIn($ebay->getStoreManager()->getStoreId(), [Website::STORE_WESHOP_MY, Website::STORE_WESHOP_PH, Website::STORE_WESHOP_TH])) {
//                        $rs['products'][$key]['sale_price_local'] = $this->getStore()->showMoney($ebay->getTotalAdditionFees(null,['gst']));
//                        $totalPriceTemp = $ebay->sell_price;
//                        $ebay->sell_price = $ebay->start_price;
//                        $totalStartPriceBeforeGst = $ebay->getLocalizeTotalPriceBeforeGst();
//                        $ebay->sell_price = $totalPriceTemp;
//                        $result['products'][$key]['start_price_local'] = $ebay->getStoreManager()->showMoney($totalStartPriceBeforeGst);
//                    }
                }

            }
            if (($conditions = ArrayHelper::remove($result, 'conditionHistogram', [])) && !$this->isEmpty($conditions)) {
                $buildCondition = [
                    'name' => 'Condition',
                    'conditions' => $conditions
                ];
                $result['conditions'] = $buildCondition;
            }
            if (count($filterPriceRange = $this->getFilterPriceRange()) > 0) {
                $result = ArrayHelper::merge($result, [
                    'filterPriceRange' => $this->getFilterPriceRange()
                ]);
            }
            return $result;
        }
        return null;
    }

    public function isAliasAvailable($alias)
    {
        return true;
//        $query = new \yii\db\Query();
//        $query->from('category');
//        $query->where(['alias' => $alias]);
//        return $query->count() >= 1;
    }

    public function getFilterPriceRange()
    {
        $store = 1;
        $priceRanges = [
            1 => [
                ['min' => 0, 'max' => 500000],
                ['min' => 500000, 'max' => 1000000],
                ['min' => 1000000, 'max' => 2500000],
                ['min' => 2500000, 'max' => 5000000],
                ['min' => 5000000, 'max' => 0],
            ],
            2 => [
                ['min' => 0, 'max' => 500000],
                ['min' => 500000, 'max' => 1000000],
                ['min' => 1000000, 'max' => 2500000],
                ['min' => 2500000, 'max' => 5000000],
                ['min' => 5000000, 'max' => 0],
            ],
            3 => [
                ['min' => 0, 'max' => 500],
                ['min' => 500, 'max' => 1000],
                ['min' => 1000, 'max' => 1500],
                ['min' => 2000, 'max' => 0],
            ],
            4 => [
                ['min' => 0, 'max' => 500],
                ['min' => 500, 'max' => 1000],
                ['min' => 1000, 'max' => 1500],
                ['min' => 2000, 'max' => 0],
            ],
            5 => [
                ['min' => 0, 'max' => 5000],
                ['min' => 5000, 'max' => 10000],
                ['min' => 10000, 'max' => 15000],
                ['min' => 15000, 'max' => 20000],
                ['min' => 25000, 'max' => 0],
            ],
            6 => [
                ['min' => 0, 'max' => 500000],
                ['min' => 50000, 'max' => 1000000],
                ['min' => 1000000, 'max' => 2000000],
                ['min' => 5000000, 'max' => 0],
            ],
        ];
        $filter = [];
        if (!$this->isEmpty($priceRanges = ArrayHelper::getValue($priceRanges, $store))) {
            //$currentRate = $store->exchangeRate();
            foreach ($priceRanges as $range) {
                list($min, $max) = array_values($range);
                //$minConvert = round($min/$currentRate,2);
                //$maxConvert = round($max/$currentRate,2);
                if ($min === 0) {
                    $label = "lesser than {max}";
                    $replacement = ['max' => $max];
                } elseif ($max === 0) {
                    $label = "greater than {min}";
                    $replacement = ['min' => $min];
                } else {
                    $label = "form {min} to {max}";
                    $replacement = ['min' => $min, 'max' => $max];
                }
                $filter[] = [
                    'min' => $min,
                    'max' => $max,
                    'label' => Yii::t('frontend', $label, $replacement),
                ];
            }
        }
        return $filter;
    }
}