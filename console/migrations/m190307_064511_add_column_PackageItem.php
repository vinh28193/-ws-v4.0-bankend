<?php

use yii\db\Migration;

/**
 * Class m190307_064511_add_column_PackageItem
 */
class m190307_064511_add_column_PackageItem extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('package_item','price','decimal(18,2)');
        $this->addColumn('package_item','cod','decimal(18,2)');
        $this->addCommentOnColumn('package_item','price','Giá trị 1 sản phẩm');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190307_064511_add_column_PackageItem cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190307_064511_add_column_PackageItem cannot be reverted.\n";

        return false;
    }
    */
}
