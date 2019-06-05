<?php

use yii\db\Migration;

/**
 * Class m190422_072345_add_column_package
 */
class m190422_072345_add_column_package extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('package_item','hold','int comment \'Đánh dấu hàng hold. 1 là hold\'');
        $this->addColumn('draft_package_item','hold','int comment \'Đánh dấu hàng hold. 1 là hold\'');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190422_072345_add_column_package cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190422_072345_add_column_package cannot be reverted.\n";

        return false;
    }
    */
}
