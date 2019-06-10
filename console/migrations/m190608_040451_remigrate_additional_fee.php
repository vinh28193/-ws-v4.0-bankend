<?php

use yii\db\Migration;

/**
 * Class m190608_040451_remigrate_additional_fee
 */
class m190608_040451_remigrate_additional_fee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('product_fee', 'target_additional_fee');
        $this->addColumn('target_additional_fee', 'target', $this->string(32)->notNull()->comment('product/order/payment')->after('order_id'));
        $this->renameColumn('target_additional_fee', 'order_id', 'store_id');
        $this->renameColumn('target_additional_fee', 'product_id', 'target_id');
        $this->renameColumn('target_additional_fee', 'store_id', 'order_id');
        $this->renameColumn('target_additional_fee', 'name', 'label');
        $this->renameColumn('target_additional_fee', 'type', 'name');
        $this->addColumn('target_additional_fee', 'type', $this->string(32)->notNull());
        $this->addColumn('store_additional_fee', 'type', $this->string(32)->notNull()->comment('origin/addition/discount')->after('name'));

        $this->update('target_additional_fee', [
            'target' => 'product',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('store_additional_fee', 'type');
        $this->dropColumn('target_additional_fee', 'type');
        $this->renameColumn('target_additional_fee', 'name', 'type');
        $this->renameColumn('target_additional_fee', 'label', 'name');
        $this->renameColumn('target_additional_fee', 'target_id', 'product_id');
        $this->renameColumn('target_additional_fee', 'store_id', 'order_id');
        $this->renameColumn('target_additional_fee', 'type', 'name');
        $this->addColumn('target_additional_fee', 'type', $this->string(32)->notNull());
        $this->dropColumn('target_additional_fee', 'target');
        $this->renameTable('target_additional_fee', 'product_fee');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190608_040451_remigrate_additional_fee cannot be reverted.\n";

        return false;
    }
    */
}
