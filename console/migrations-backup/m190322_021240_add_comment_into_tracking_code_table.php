<?php

use yii\db\Migration;

/**
 * Class m190322_021240_add_comment_into_tracking_code_table
 */
class m190322_021240_add_comment_into_tracking_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addCommentOnTable('tracking_code','bảng này là bảng chứa tất cả mã tracking của bên kho Mỹ và vận chuyển local');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addCommentOnTable('tracking_code','');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190322_021240_add_comment_into_tracking_code_table cannot be reverted.\n";

        return false;
    }
    */
}
