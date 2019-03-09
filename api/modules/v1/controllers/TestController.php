<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 05/03/2019
 * Time: 11:27
 */

namespace api\modules\v1\controllers;

use common\components\Product;
use common\components\StoreManager;
use common\lib\AmazonProductGate;
use common\lib\AmazonSearchProductGate;
use common\lib\EbayProductGate;
use common\lib\EbaySearchForm;
use common\lib\EbaySearchProductGate;
use common\models\amazon\AmazonSearchForm;
use common\models\Order;
use Yii;

class TestController extends RestController
{
    public function actionGetamazon()
    {
        $amazonSearch = new AmazonSearchForm();
        $amazonSearch->store = \Yii::$app->request->post('domain', Product::STORE_US);
        $amazonSearch->keyword = \Yii::$app->request->post('keyword');
        $amazonSearch->page = \Yii::$app->request->post('page');
        $amazonSearch->max_price = \Yii::$app->request->post('max_price');
        $amazonSearch->min_price = \Yii::$app->request->post('min_price');
        $amazonSearch->asin_id = \Yii::$app->request->post('sku');
        $amazonSearch->item_name = \Yii::$app->request->post('item_name');
        $amazonSearch->node_ids = \Yii::$app->request->post('category_ids');
        $amazonSearch->parent_asin_id = \Yii::$app->request->post('parent_sku');
        $amazonSearch->load_sub_url = \Yii::$app->request->post('load_sub_url');
        $amazonSearch->is_first_load = \Yii::$app->request->post('is_first_load');
        $amazonSearch->rh = \Yii::$app->request->post('rh');
        $amazonSearch->sort = \Yii::$app->request->post('sort');
        $rs = AmazonSearchProductGate::parse($amazonSearch);
        print_r($rs);
        die;
    }

    public function actionGetamazondetail()
    {
        $amazonSearch = new AmazonSearchForm();
        $amazonSearch->store = \Yii::$app->request->post('domain', Product::STORE_US);
        $amazonSearch->asin_id = \Yii::$app->request->post('sku');
        $rs = AmazonProductGate::parse($amazonSearch);
        print_r($rs);
        die;
    }

    public function actionGetebay()
    {
        $form = new EbaySearchForm();
        $form->keywords = \Yii::$app->request->post("keyword");
        $form->page = \Yii::$app->request->post("page", 1);
        $form->categories = \Yii::$app->request->post("category_ids") ? explode(',', \Yii::$app->request->post("category_ids")) : null;
        $form->aspectFilters = [];
        $form->itemFilters = [];
        $form->min_price = \Yii::$app->request->post("min_price");
        $form->max_price = \Yii::$app->request->post("max_price");
        $form->order = \Yii::$app->request->post("sort");
        $form->sellers = \Yii::$app->request->post("sellers");
        $form->type = \Yii::$app->request->post("type");
        $rs = EbaySearchProductGate::parse($form);
        print_r($rs);
        die;
    }

    public function actionEbaydetail()
    {
        $sku = \Yii::$app->request->get('sku');
        if ($sku) {
            $store = new StoreManager();
            $store->setStore(1);
            $product = EbayProductGate::parseObject($sku, $store);
            print_r($product);
        }
    }

    public function actionCreateSimpleOrder()
    {
        Yii::$app->cart->removeItems();
        Yii::$app->cart->addItem('IF_739F9D0E', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/cAQAAOSwMn5bzly6/$_12.JPG?set_id=880000500F', '252888606889');
        Yii::$app->cart->addItem('IF_6C960C53', 'cleats_blowout_sports', 1, 'ebay', 'https://i.ebayimg.com/00/s/MTYwMFgxMDY2/z/nrsAAOSw7Spbzlyw/$_12.JPG?set_id=880000500F', '252888606889');
        Yii::$app->cart->addItem('261671375738', 'luv4everbeauty', 1, 'ebay', 'https://i.ebayimg.com/00/s/NTk3WDU5Nw==/z/FjMAAOSwscNbK5~0/$_57.JPG');
        $items = Yii::$app->cart->getItems();

        $products = [];
        foreach ($items as $key => $simpleItem) {
            /** @var  $simpleItem \common\components\cart\item\SimpleItem */
            $item = $simpleItem->item;
            $product =  new \common\models\Product;
            $product->createFormCart($item);
            $product->save(false);
            $products[$key] = $product;
        }
        var_dump($products);
    }
}