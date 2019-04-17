<?php

use yii\db\Migration;

/**
 * Class m190314_103412_product_name_tables_product
 */
class m190314_103412_product_name_tables_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
<<<<<<< HEAD
        // $this->addColumn('product','product_name','text not null');
        //$this->dropForeignKey('idx-product-currency' , 'product');
        //$this->dropIndex('idx-product-currency','product');
        // $this->dropColumn('product','currency_id');
        // $this->dropColumn('product','currency_symbol');
        // $this->dropColumn('product','exchange_rate');
=======
        $this->addColumn('product','product_name','text not null');
        $this->dropForeignKey('idx-product-currency' , 'product');
        $this->dropIndex('idx-product-currency','product');
        $this->dropColumn('product','currency_id');
        $this->dropColumn('product','currency_symbol');
        $this->dropColumn('product','exchange_rate');
>>>>>>> 80a5afbea6edfe79ee4bbadba481eca859266add
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190314_103412_product_name_tables_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190314_103412_product_name_tables_product cannot be reverted.\n";

        return false;
    }
    */
}
