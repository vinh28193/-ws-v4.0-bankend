<?php

use yii\db\Migration;

/**
 * Class m190513_022018_update_foreign_key_for_payments_table
 */
class m190513_022018_update_foreign_key_for_payments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-payment_method_provider-payment_method','payment_method_provider','payment_method_id','payment_method','id');
        $this->createIndex('idx-payment_method_provider-payment_method','payment_method_provider','payment_method_id');

        $this->addForeignKey('fk-payment_method_provider-payment_provider','payment_method_provider','payment_provider_id','payment_provider','id');
        $this->createIndex('idx-payment_method_provider-payment_provider','payment_method_provider','payment_provider_id');

        $this->addForeignKey('fk-payment_method_bank-payment_method','payment_method_bank','payment_method_id','payment_method','id');
        $this->createIndex('idx-payment_method_bank-payment_method','payment_method_bank','payment_method_id');

        $this->addForeignKey('fk-payment_method_bank-payment_bank','payment_method_bank','payment_bank_id','payment_bank','id');
        $this->createIndex('idx-payment_method_bank-payment_bank','payment_method_bank','payment_bank_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-payment_method_provider-payment_method','payment_method_provider');
        $this->dropIndex('idx-payment_method_provider-payment_method','payment_method_provider');

        $this->dropForeignKey('fk-payment_method_provider-payment_provider','payment_method_provider');
        $this->dropIndex('idx-payment_method_provider-payment_provider','payment_method_provider');

        $this->dropForeignKey('fk-payment_method_bank-payment_method','payment_method_bank');
        $this->dropIndex('idx-payment_method_bank-payment_method','payment_method_bank');

        $this->dropForeignKey('fk-payment_method_bank-payment_bank','payment_method_bank');
        $this->dropIndex('idx-payment_method_bank-payment_bank','payment_method_bank');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190513_022018_update_foreign_key_for_payments_table cannot be reverted.\n";

        return false;
    }
    */
}
