<?php

use yii\db\Migration;

/**
 * Class m190420_040539_draft_package
 */
class m190420_040539_draft_package extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_package_item','tracking_merge','text COMMENT \' List tracking khi merge từ thừa và thiếu \'');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190420_040539_draft_package cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190420_040539_draft_package cannot be reverted.\n";

        return false;
    }
    */
}
