<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 08:44
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\Category;
use common\products\forms\ProductDetailFrom;
use common\products\BaseProduct;
use common\products\forms\ProductSearchForm;
use Yii;

class ProductGateController extends BaseApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $authenticator = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        $authenticator['except'] = ['options', 'calculator'];
        $behaviors['authenticator'] = $authenticator;

        return $behaviors;// TODO: Change the autogenerated stub
    }

    /**
     * @return array
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function verbs()
    {
        return [
            'detail' => ['GET', 'POST'],
            'search' => ['GET', 'POST'],
            'calculator' => ['GET', 'POST']
        ];
    }

    public function actionDetail()
    {
        $paramRequests = Yii::$app->getRequest()->getBodyParams();
        $form = new ProductDetailFrom();
        $form->load($paramRequests, '');
        /** @var $product false | BaseProduct */
        if (($product = $form->detail()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        return $this->response(true, "get detail success", $product);
    }

    public function actionSearch()
    {
        $paramRequests = Yii::$app->getRequest()->getBodyParams();
        $form = new ProductSearchForm();
        $form->load($paramRequests, '');
        if (($response = $form->search()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        return $this->response(true, "success", $response);
    }

    public function actionCalculator()
    {
        $paramRequests = Yii::$app->getRequest()->getQueryParams();
        $form = new ProductDetailFrom();
        $form->load($paramRequests, '');
        /** @var $product false | BaseProduct */
        if (($product = $form->detail()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        $custom = $product->getCategory();
        $product->getAdditionalFees()->withCondition($product, 'custom_fee', null);

        $data = ['type' => $product->type];

        $calculate = [];
        foreach ($product->getAdditionalFees()->keys() as $key) {
            $calculate[$key] = implode('/', $product->getAdditionalFees()->getTotalAdditionalFees($key));
        }
        $calculate['exchange'] = $product->getExchangeRate();
        $data['calculate'] = $calculate;
        $data['item'] = [
            'type' => $product->type,
            'item_id' => $product->item_id,
            'item_sku' => $product->item_sku,
            'item_name' => $product->item_name,
            'item_origin_url' => $product->item_origin_url,
            'start_price' => $product->start_price,
            'shipping_weight' => $product->getShippingWeight(),
            'shipping_quantity' => $product->getShippingQuantity(),
            'is_new' => $product->getIsNew(),
            'is_special' => $product->getIsSpecial(),
            'user_level' => $product->getUserLevel(),
        ];
        $category = [];
        if ($custom) {
            $category = [
                'id' => $custom->id,
                'name' => $custom->name,
                'origin_name' => $custom->origin_name,
            ];
            if (($group = $custom->categoryGroup) !== null) {
                $category['group']['name'] = $group->name;
                $category['group']['special'] = $group->is_special;
                if ($group->special_min_amount > 0) {
                    $category['group']['special_when'] = $group->special_min_amount;
                }
                if ($group->rule_description !== null) {
                    $category['group']['rule_description'] = $group->rule_description;
                }
            }
        }
        $data['category'] = $category;
        return $this->response(true, "get calculate success", $data);
    }
}