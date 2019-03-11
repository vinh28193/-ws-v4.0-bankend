<?php

use yii\db\Migration;

/**
 * Class m190311_022007_add_label_column_from_store_additional_fee_table
 */
class m190311_022007_add_label_column_from_store_additional_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('store_additional_fee','label',$this->string(80)->notNull()->after('name')->comment('Label of fee'));
        $this->update('store_additional_fee',['label' => 'Giá gốc Sell Price '],['name' => 'origin_fee']);
        $this->update('store_additional_fee',['label' => 'Phí / Thuế Bang Tax  Ví dụ tại Mỹ tại nhật'],['name' => 'origin_tax_fee']);
        $this->update('store_additional_fee',['label' => 'phí vận chuyển tại nước sở tại'],['name' => 'origin_shipping_fee']);
        $this->update('store_additional_fee',['label' => 'Phí weshop'],['name' => 'weshop_fee']);
        $this->update('store_additional_fee',['label' => 'Phí vận chuyển quốc tế'],['name' => 'intl_shipping_fee']);
        $this->update('store_additional_fee',['label' => 'Phí Phụ Thu danh mục'],['name' => 'custom_fee']);
        $this->update('store_additional_fee',['label' => 'Phí vận chuyển Tại Nội địa như Weshop Viêt Nam hoặc Weshop Indo'],['name' => 'delivery_fee']);
        $this->update('store_additional_fee',['label' => 'Phí đóng kiện'],['name' => 'packing_fee']);
        $this->update('store_additional_fee',['label' => 'Phí kiểm hàng'],['name' => 'inspection_fee']);
        $this->update('store_additional_fee',['label' => 'Phí bảo hiểm'],['name' => 'insurance_fee']);
        $this->update('store_additional_fee',['label' => 'Phí VAT tại nước sở tại WS VIET NAM hoặc Indo '],['name' => 'vat_fee']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('store_additional_fee','label');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190311_022007_add_label_column_from_store_additional_fee_table cannot be reverted.\n";

        return false;
    }
    */
}
