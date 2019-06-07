<?php

use yii\db\Migration;

/**
 * Class m190226_070756_update_data_from_store_additional_fee_table
 */
class m190226_070756_update_data_from_store_additional_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    $sql =<<< 'SQL'
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'origin_fee', `currency` = 'VND', `description` = 'Phí gốc tại xuất sứ',`is_convert` = 1, `is_read_only` = 0, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 1;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'origin_tax_fee', `currency` = 'VND', `description` = 'Phí tax tại xuất sứ', `is_convert` = 1, `is_read_only` = 0, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 2;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'origin_shipping_fee', `currency` = 'VND', `description` = 'Phí shipping tại xuất sứ',  `is_convert` = 1, `is_read_only` = 0, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 3;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'weshop_fee', `currency` = 'VND', `description` = 'Phí weshop',`is_convert` = 1, `is_read_only` = 1, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 4;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'intl_shipping_fee', `currency` = 'VND', `description` = 'Phí vận chuyển quốc tế',  `is_convert` = 1, `is_read_only` = 1, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 5;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'custom_fee', `currency` = 'VND', `description` = 'Phí phụ thu',  `is_convert` = 1, `is_read_only` = 1, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 6;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'delivery_fee', `currency` = 'VND', `description` = 'Phí vận chuyển nội địa', `is_convert` = 1, `is_read_only` = 1, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 7;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'packing_fee', `currency` = 'VND', `description` = 'Phí đóng hàng', `is_convert` = 1, `is_read_only` = 0, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 8;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'inspection_fee', `currency` = 'VND', `description` = 'Phí kiểm hàng', `is_convert` = 1, `is_read_only` = 0, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 9;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'insurance_fee', `currency` = 'VND', `description` = 'Phí bảo hiểm', `is_convert` = 1, `is_read_only` = 0, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 10;
UPDATE `store_additional_fee` SET `store_id` = 1, `name` = 'vat_fee', `currency` = 'VND', `description` = 'Phí VAT', `is_convert` = 1, `is_read_only` = 0, `status` = 1, `created_by` = 1, `created_time` = 1551150587, `updated_by` = 1, `updated_time` = 1551150587, `fee_rate` = 0.00 WHERE `id` = 11;
SQL;
    $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190226_070756_update_data_from_store_additional_fee_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190226_070756_update_data_from_store_additional_fee_table cannot be reverted.\n";

        return false;
    }
    */
}
