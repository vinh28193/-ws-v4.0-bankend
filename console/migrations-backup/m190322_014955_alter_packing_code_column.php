<?php

use yii\db\Migration;

/**
 * Class m190322_014955_alter_packing_code_column
 */
class m190322_014955_alter_packing_code_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('package', 'package_code', $this->string(32)->null()->comment('Mã kiện của weshop'));
        $this->alterColumn('package_item', 'package_code', $this->string(32)->null()->comment('Mã kiện của weshop'));
        $this->alterColumn('tracking_code', 'package_code', $this->string(32)->null()->comment('Mã kiện của weshop'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('package', 'package_code', $this->integer(11)->comment('mã kiện của weshop'));
        $this->alterColumn('package_item', 'package_code', $this->integer(11)->comment('mã kiện của weshop'));
        $this->alterColumn('package_item', 'package_code', $this->integer(11)->comment('mã kiện của weshop'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190322_014955_alter_packing_code_column cannot be reverted.\n";

        return false;
    }
    */
}
