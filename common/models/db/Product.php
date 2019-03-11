<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $order_id order id
 * @property int $seller_id
 * @property string $portal portal sản phẩm, ebay, amazon us, amazon jp ....
 * @property string $sku sku của sản phẩm
 * @property string $parent_sku sku cha
 * @property string $link_img link ảnh sản phẩm
 * @property string $link_origin link gốc sản phẩm
 * @property int $category_id id danh mục
 * @property int $custom_category_id id danh mục phụ thu
 * @property string $price_amount đơn giá ngoại tệ
 * @property string $price_amount_local đơn giá local
 * @property string $total_price_amount_local tổng tiền hàng của sản phẩm
 * @property int $quantity số lượng khách đặt
 * @property int $quantity_purchase số lượng đã mua
 * @property int $quantity_inspect số lượng đã kiểm
 * @property string $variations thuộc tính sản phẩm
 * @property int $variation_id mã thuộc tính sản phẩm
 * @property string $note_by_customer note của khách
 * @property string $total_weight_temporary cân nặng tạm tính
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 * @property int $currency_id
 * @property string $currency_symbol
 * @property string $exchange_rate
 *
 * @property Category $category
 * @property CategoryCustomPolicy $customCategory
 * @property Order $order
 * @property Seller $seller
 * @property SystemCurrency $currency
 */
class Product extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'seller_id', 'category_id', 'custom_category_id', 'quantity', 'quantity_purchase', 'quantity_inspect', 'variation_id', 'created_at', 'updated_at', 'remove', 'currency_id'], 'integer'],
            [['link_img', 'link_origin', 'variations', 'note_by_customer', 'total_weight_temporary'], 'string'],
            [['price_amount', 'price_amount_local', 'total_price_amount_local', 'exchange_rate'], 'number'],
            [['portal', 'sku', 'parent_sku', 'currency_symbol'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['custom_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryCustomPolicy::className(), 'targetAttribute' => ['custom_category_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seller::className(), 'targetAttribute' => ['seller_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCurrency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'seller_id' => 'Seller ID',
            'portal' => 'Portal',
            'sku' => 'Sku',
            'parent_sku' => 'Parent Sku',
            'link_img' => 'Link Img',
            'link_origin' => 'Link Origin',
            'category_id' => 'Category ID',
            'custom_category_id' => 'Custom Category ID',
            'price_amount' => 'Price Amount',
            'price_amount_local' => 'Price Amount Local',
            'total_price_amount_local' => 'Total Price Amount Local',
            'quantity' => 'Quantity',
            'quantity_purchase' => 'Quantity Purchase',
            'quantity_inspect' => 'Quantity Inspect',
            'variations' => 'Variations',
            'variation_id' => 'Variation ID',
            'note_by_customer' => 'Note By Customer',
            'total_weight_temporary' => 'Total Weight Temporary',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'remove' => 'Remove',
            'currency_id' => 'Currency ID',
            'currency_symbol' => 'Currency Symbol',
            'exchange_rate' => 'Exchange Rate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomCategory()
    {
        return $this->hasOne(CategoryCustomPolicy::className(), ['id' => 'custom_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(SystemCurrency::className(), ['id' => 'currency_id']);
    }
}
