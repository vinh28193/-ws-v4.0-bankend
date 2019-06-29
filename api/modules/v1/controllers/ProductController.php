<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/24/2019
 * Time: 10:39 AM
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\Product;
use Yii;
use common\helpers\ChatHelper;
use common\data\ActiveDataProvider;

class ProductController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'update'],
                'roles' => $this->getAllRoles(true)
            ],
        ];
    }

    public function actionCreate() {
        $now = Yii::$app->getFormatter()->asTimestamp('now');
        $post = \Yii::$app->request->post();
        $query = new Product();
        $query->link_img = $post['link_image'];
        $query->product_name = $post['name_pro'];
        $query->product_link = $post['link_pro'];
        $query->link_origin = $post['link_origin'];
        $query->price_amount_origin = $post['price_amount_origin_pro'];
        $query->variations = $post['variations_pro'];
        $query->portal = $post['portal_pro'];
        $query->sku = $post['sku_pro'];
        $query->parent_sku = $post['sku_parent_pro'];
        $query->price_amount_local = $post['price_amount_local_pro'];
        $query->order_id = $post['id'];
        $query->seller_id = $post['seller_id'];
        $query->total_price_amount_local = $post['total_price_amount_local_pro'];
        $query->quantity_purchase = $post['quantity_purchase'];
        $query->quantity_customer = $post['quantity_customer'];
        $query->created_at = $now;
        if (!$query->save()) {
            return $this->response(false, 'error', $query->getErrors());
        }
        return $this->response(true, 'success', $query);
    }

    public function actionUpdate($id) {
        $post = Yii::$app->request->post();
        if ($id) {
            $product = Product::find()->where(['id' => $id])->one();
            if (isset($post['quantityI'])) {
                $product->quantity_inspect =  $post['quantityI'];
            }
            if (isset($post['quantityP'])) {
                $product->quantity_purchase =  $post['quantityP'];
            }
            if (isset($post['quantityC'])) {
                $product->quantity_customer =  (int)$post['quantityC'];
            }
            if (isset($post['variant'])) {
                $product->variations = $post['variant'];
            }
            if (isset($post['policy_id'])) {
                $product->custom_category_id = $post['policy_id'];
            }
            if (isset($post['category_id'])) {
                $product->custom_category_id = $post['category_id'];
            }
            if (isset($post['noteCustomer'])) {
                $product->note_by_customer = $post['noteCustomer'];
            }
            if (isset($post['note_boxme'])) {
                $product->note_boxme = $post['note_boxme'];
            }
            $dirtyAttributes = $product->getDirtyAttributes();
            $messages = "order {$post['order_path']} Update Product {$this->resolveChatMessage($dirtyAttributes,$product)}";

            if (!$product->save()) {
                return $this->response(false, 'error', $product->getErrors());
            }
            ChatHelper::push($messages, $post['order_path'], 'GROUP_WS', 'SYSTEM');
            Yii::$app->wsLog->push('order', "update product {$post['title']}", null, [
                'id' => $post['order_path'],
                'request' => $this->post,
                'response' => $messages
            ]);
            return $this->response(true, 'success', $product);
        }
    }
    protected function resolveChatMessage($dirtyAttributes, $reference)
    {

        $results = [];
        foreach ($dirtyAttributes as $name => $value) {
            if (strpos($name, '_id') !== false && is_numeric($value)) {
                continue;
            }
            $results[] = "`{$reference->getAttributeLabel($name)}` changed from `{$reference->getOldAttribute($name)}` to `$value`";
        }

        return implode(", ", $results);
    }
}