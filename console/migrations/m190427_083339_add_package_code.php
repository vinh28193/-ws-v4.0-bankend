<?php

use yii\db\Migration;

/**
 * Class m190427_083339_add_package_code
 */
class m190427_083339_add_package_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('package', 'package_code',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190427_083339_add_package_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190427_083339_add_package_code cannot be reverted.\n";

        return false;
    }
    */
}
