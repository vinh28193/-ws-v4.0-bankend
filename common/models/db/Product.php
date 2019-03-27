<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $order_id order id
 * @property int $seller_id
 * @property string $portal portal sản phẩm, ebay, amazon us, amazon jp , etc....
 * @property string $sku sku của sản phẩm
 * @property string $parent_sku sku cha
 * @property string $link_img link ảnh sản phẩm
 * @property string $link_origin link gốc sản phẩm
 * @property int $category_id id danh mục trên Website Weshop bắt qua API
 * @property int $custom_category_id id danh mục phụ thu Hải Quản nếu api ko bắt được dang mục mà do sale chọn trong OPS thì sẽ thu thêm COD
 * @property string $price_amount_origin đơn giá gốc ngoại tệ
 * @property string $price_amount_local đơn giá local
 * @property string $total_price_amount_local tổng tiền hàng của từng sản phẩm
 * @property int $quantity_customer số lượng khách đặt
 * @property int $quantity_purchase số lượng Nhân viên đã mua
 * @property int $quantity_inspect số lượng đã kiểm
 * @property string $variations thuộc tính sản phẩm
 * @property int $variation_id mã thuộc tính sản phẩm . Notes : Trường này để làm addon tự động mua hàng đẩy vào Giở hàng của Ebay / Amazon 
 * @property string $note_by_customer note của khách / Khách hàng ghi chú
 * @property string $total_weight_temporary
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove mặc định 0 là chưa xóa 1 là ẩn 
 * @property string $product_name
 * @property string $product_link
 * @property string $version version 4.0
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

    public function getOrderFee()
    {
        return $this->hasMany(OrderFee::className(), ['product_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'seller_id', 'portal', 'sku', 'parent_sku', 'link_img', 'link_origin', 'price_amount_origin', 'price_amount_local', 'total_price_amount_local', 'quantity_customer', 'created_at', 'product_name'], 'required'],
            [['order_id', 'seller_id', 'category_id', 'custom_category_id', 'quantity_customer', 'quantity_purchase', 'quantity_inspect', 'variation_id', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['link_img', 'link_origin', 'variations', 'note_by_customer', 'product_name'], 'string'],
            [['price_amount_origin', 'price_amount_local', 'total_price_amount_local', 'total_weight_temporary'], 'number'],
            [['portal', 'sku', 'parent_sku', 'version', 'condition'], 'string', 'max' => 255],
            [['product_link'], 'string', 'max' => 500],
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
            'price_amount_origin' => 'Price Amount Origin',
            'price_amount_local' => 'Price Amount Local',
            'total_price_amount_local' => 'Total Price Amount Local',
            'quantity_customer' => 'Quantity Customer',
            'quantity_purchase' => 'Quantity Purchase',
            'quantity_inspect' => 'Quantity Inspect',
            'variations' => 'Variations',
            'variation_id' => 'Variation ID',
            'note_by_customer' => 'Note By Customer',
            'total_weight_temporary' => 'Total Weight Temporary',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'product_name' => 'Product Name',
            'product_link' => 'Product Link',
            'version' => 'Version',
            'condition' => 'Condition',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::className(), ['product_id' => 'id']);
    }
}
