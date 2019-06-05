<?php

use common\components\db\Migration;

/**
 * Handles adding m190219_021325_add_condition_name_column_condition_data_column_condition_description_column_to_store_additional_fee_table to table `store_additional_fee`.
 */
class m190219_021325_add_condition_name_column_condition_data_column_condition_description_column_to_store_additional_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('store_additional_fee', 'condition_name', $this->string(255)->null()->after('description')->comment('Fee Name'));
        $this->addColumn('store_additional_fee', 'condition_data', $this->binary()->after('condition_name')->comment('Fee Data'));
        $this->addColumn('store_additional_fee', 'condition_description', $this->text()->null()->after('condition_data')->comment('Fee Rules Description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('store_additional_fee', 'condition_name');
        $this->dropColumn('store_additional_fee', 'condition_data');
        $this->dropColumn('store_additional_fee', 'condition_description');
    }
}
